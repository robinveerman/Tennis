<?php

namespace App\Controller;

use App\Entity\Speler;
use App\Entity\Toernooi;
use App\Entity\Wedstrijd;
use App\Repository\WedstrijdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {
	/**
	 * @Route("/", name="default")
	 */
	public function index() {
		$em = $this->getDoctrine()->getManager();

		$wedstrijden = $em->getRepository( Wedstrijd::class )->findAll();
		$toernooien  = $em->getRepository( Toernooi::class )->findAll();

		return $this->render( 'default/index.html.twig', [
			'wedstrijd'  => $wedstrijden,
			'toernooien' => $toernooien,
		] );
	}


	/**
	 * @Route("/winnaar/{wedstrijd}/{speler}", name="winnen")
	 */
	public function winnen( Wedstrijd $wedstrijd, Speler $speler ) {
		$em = $this->getDoctrine()->getManager();

		$wedstrijd->setWinnaarID( $speler );

		$em->persist( $wedstrijd );
		$em->flush();

		return $this->redirectToRoute( 'default' );
	}


	/**
	 * @Route("/get/{id}", name="get_toernooi")
	 */
	public function getToernooi( Toernooi $toernooi ) {
		$em = $this->getDoctrine()->getManager();

		$wedstrijden = $em->getRepository( Wedstrijd::class )->findBy( [ 'toernooiID' => $toernooi ] );

		return $this->render( 'default/view.html.twig', [
			'controller_name' => 'DefaultController',
			'wedstrijd'       => $wedstrijden,
			'toernooi'        => $toernooi
		] );
	}

	/**
	 * @Route("/makeGame/{toernooi}", name="makeGame")
	 */
	public function makeGame( WedstrijdRepository $wedstrijdRepository, Toernooi $toernooi ): Response {
		$em      = $this->getDoctrine()->getManager();
		$spelers = $em->getRepository( Speler::class )->findAll();

		// alle spelers
		$wedstrijd = [];
		foreach ( $spelers as $speler ) {
			array_push( $wedstrijd, $speler->getId() );
		}

		if ( shuffle( $wedstrijd ) ) {
			if ( count( $wedstrijd ) == 128 ) {

				// genoeg spelers.
				for ( $i = 1; $i < count( $wedstrijd ); $i ++ ) {
					$game = new Wedstrijd();
					$game->setRonde( 1 );
					$game->setSpeler1ID( $em->getRepository( Speler::class )->findOneBy( [ 'id' => $wedstrijd[ $i - 1 ] ] ) );
					$game->setSpeler2ID( $em->getRepository( Speler::class )->findOneBy( [ 'id' => $wedstrijd[ ++ $i - 1 ] ] ) );
					$game->setToernooiID( $toernooi );
					$em->persist( $game );
					$em->flush();
				}

			}
		} else {
			return $this->render( 'default/index.html.twig', [
				'controller_name' => 'DefaultController',
			] );
		}

		return $this->render( 'wedstrijd/index.html.twig', [
			'wedstrijds' => $wedstrijdRepository->findAll(),
		] );
	}

	/**
	 * @Route("/round/{toernooi}/{ronde}", name="makeDuel")
	 */
	public function makeRound( Toernooi $toernooi, $ronde ) {

		$em         = $this->getDoctrine()->getManager();
		$toernooien = $em->getRepository( Toernooi::class )->findAll();

		// Haalt alle winnaars van vorige ronde op
		$RAW_QUERY = 'select winnaar_id_id from wedstrijd where ronde = 1 and toernooi_id_id = :toernooi ;';

		$statement = $em->getConnection()->prepare( $RAW_QUERY );
		$statement->bindValue( 'toernooi', $toernooi->getId() );
		$statement->execute();

		// Alle winnaars van wedstijd in array
		$wedstrijden = $statement->fetchAll();

		// Stopt spelers in Array
		$wedstrijd = [];
		foreach ( $wedstrijden as $speler ) {
			array_push( $wedstrijd, $speler );
		}

		if ( shuffle( $wedstrijd ) ) {

			// Nieuwe game maken
			for ( $i = 1; $i < count( $wedstrijd ); $i ++ ) {
				$game = new Wedstrijd();
				$game->setRonde( 2 );
				$game->setSpeler1ID( $em->getRepository( Speler::class )->findOneBy( [ 'id' => $wedstrijd[ $i - 1 ] ] ) );
				$game->setSpeler2ID( $em->getRepository( Speler::class )->findOneBy( [ 'id' => $wedstrijd[ ++ $i - 1 ] ] ) );
				$game->setToernooiID( $toernooi );
				$em->persist( $game );
				$em->flush();
			}
		} else {
			return $this->redirectToRoute( 'default' );
		}


		return $this->render( 'default/index.html.twig', [
			'wedstrijd'  => $wedstrijden,
			'toernooien' => $toernooien,
			'ronde2'     => $wedstrijden,
		] );

	}

}
