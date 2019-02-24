<?php

namespace App\Controller;

use App\Entity\Speler;
use App\Form\SpelerType;
use App\Repository\SpelerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/speler")
 */
class SpelerController extends AbstractController
{
    /**
     * @Route("/", name="speler_index", methods={"GET"})
     */
    public function index(SpelerRepository $spelerRepository): Response
    {
    	if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    return $this->render( 'speler/index.html.twig', [
			    'spelers' => $spelerRepository->findAll(),
		    ] );
	    }
    	else{
    		return $this->render('default/accessdenied.html.twig');
	    }
    }

    /**
     * @Route("/new", name="speler_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
	    if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    $speler = new Speler();
		    $form   = $this->createForm( SpelerType::class, $speler );
		    $form->handleRequest( $request );

		    if ( $form->isSubmitted() && $form->isValid() ) {
			    $entityManager = $this->getDoctrine()->getManager();
			    $entityManager->persist( $speler );
			    $entityManager->flush();

			    return $this->redirectToRoute( 'speler_index' );
		    }

		    return $this->render( 'speler/new.html.twig', [
			    'speler' => $speler,
			    'form'   => $form->createView(),
		    ] );
	    }
	    else{
	    	return $this->render('default/accessdenied.html.twig');
	    }
    }

    /**
     * @Route("/{id}", name="speler_show", methods={"GET"})
     */
    public function show(Speler $speler): Response
    {
	    if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    return $this->render( 'speler/show.html.twig', [
			    'speler' => $speler,
		    ] );
	    }
	    else{
	    	return $this->render('default/accessdenied.html.twig');
	    }
    }

    /**
     * @Route("/{id}/edit", name="speler_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Speler $speler): Response
    {
	    if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    $form = $this->createForm( SpelerType::class, $speler );
		    $form->handleRequest( $request );

		    if ( $form->isSubmitted() && $form->isValid() ) {
			    $this->getDoctrine()->getManager()->flush();

			    return $this->redirectToRoute( 'speler_index', [
				    'id' => $speler->getId(),
			    ] );
		    }

		    return $this->render( 'speler/edit.html.twig', [
			    'speler' => $speler,
			    'form'   => $form->createView(),
		    ] );
	    }
	    else{
	    	return $this->render('default/accessdenied.html.twig');
	    }
    }

    /**
     * @Route("/{id}", name="speler_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Speler $speler): Response
    {
	    if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    if ( $this->isCsrfTokenValid( 'delete' . $speler->getId(), $request->request->get( '_token' ) ) ) {
			    $entityManager = $this->getDoctrine()->getManager();
			    $entityManager->remove( $speler );
			    $entityManager->flush();
		    }

		    return $this->redirectToRoute( 'speler_index' );
	    }
	    else{
	    	return $this->render('default/accessdenied.html.twig');
	    }
    }
}
