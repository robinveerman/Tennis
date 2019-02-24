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

		$wedstrijd = [];
		foreach ( $spelers as $speler ) {
			array_push( $wedstrijd, $speler->getId() );
		}


		/* maximaal 128 deelnemers, formule voor rest is    ( er zijn b.v. 100 deelnemers)
		 * secuence is 128, 64, 32, 16, 8, 4, 2 ronde 1, 2, 3, 4, 5, 6, 7
		 * formule 1:  aantal_deelnemers - (128 - aantal_deelnemers) = deelnemers_ronde_1    (72) (36 wedstrijden)
		 * er gaan ;  naar_ronde_2 = aantal_deelnemers - deelnemers_ronde_1     (28 gaan er zo door).
		 *
		 * als er teveel aanmeldingen zijn dan alle spelers boven 128 uitsluiten.
		 */
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

			} elseif ( count( $wedstrijd ) > 64 ) {
				// eerst de rest al naar ronde twee zetten.
				$ronde1 = count( $wedstrijd ) - ( 128 - count( $wedstrijd ) );  // 72 36games.
				$ronde2 = count( $wedstrijd ) - $ronde1;  // 28 meteen naar ronde 2.
				for ( $i = 1; $i < $ronde2; $i ++ ) {
					// de eerste 28 stuks ronde 2
					$game = new Wedstrijd();
					$game->setRonde( 2 );
					$game->setSpeler1ID( $em->getRepository( Speler::class )->findOneBy( [ 'id' => $wedstrijd[ $i - 1 ] ] ) );
					$game->setSpeler2ID( $em->getRepository( Speler::class )->findOneBy( [ 'id' => $wedstrijd[ ++ $i - 1 ] ] ) );
					$game->setToernooiID( $toernooi );
					$em->persist( $game );
					$em->flush();
				}
				for ( $i = $ronde2; $i < count( $wedstrijd ); $i ++ ) {
					$game = new Wedstrijd();
					$game->setRonde( 1 );
					$game->setSpeler1ID( $em->getRepository( Speler::class )->findOneBy( [ 'id' => $wedstrijd[ $i - 1 ] ] ) );
					$game->setSpeler2ID( $em->getRepository( Speler::class )->findOneBy( [ 'id' => $wedstrijd[ ++ $i - 1 ] ] ) );
					$game->setToernooiID( $toernooi );
					$em->persist( $game );
					$em->flush();
				}
			} else {
				return $this->render( 'default/index.html.twig', [
					'controller_name' => 'DefaultController',
				] );
			}

		}

		return $this->render( 'wedstrijd/index.html.twig', [
			'wedstrijds' => $wedstrijdRepository->findAll(),
		] );
	}

	/**
	 * @Route("/round/{toernooi}/{ronde}", name="makeDuel")
	 */
	public function makeRound( Toernooi $toernooi, $ronde ) {

		$em = $this->getDoctrine()->getManager();

		$toernooien = $em->getRepository( Toernooi::class )->findAll();

		$repo        = $this->getDoctrine()->getRepository( Wedstrijd::class );
		$query       = $repo->createQueryBuilder( 'p' )
		                    ->where( 'p.ronde = :ronde' )
		                    ->setParameter( 'ronde', number_format( $ronde - 1 ) )
		                    ->getQuery();
		$wedstrijden = $query->getResult();

		$wedstrijd = [];
		foreach ( $wedstrijden as $speler ) {
			array_push( $wedstrijd, $speler->getWinnaarId() );
		}

		if ( shuffle( $wedstrijd ) ) {
			if ( count( $wedstrijd ) == 64 ) {

				// genoeg spelers.
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

		}

		return $this->render( 'default/index.html.twig', [
			'wedstrijd'  => $wedstrijden,
			'toernooien' => $toernooien,
			'ronde2'     => $wedstrijden,
		] );

	}

}
