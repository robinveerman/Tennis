<?php

namespace App\Controller;

use App\Entity\Toernooi;
use App\Form\ToernooiType;
use App\Repository\ToernooiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/toernooi")
 */
class ToernooiController extends AbstractController
{
    /**
     * @Route("/", name="toernooi_index", methods={"GET"})
     */
    public function index(ToernooiRepository $toernooiRepository): Response
    {
	    if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    return $this->render( 'toernooi/index.html.twig', [
			    'toernoois' => $toernooiRepository->findAll(),
		    ] );
	    }
	    else{
	    	return $this->render('default/accessdenied.html.twig');
	    }
    }

    /**
     * @Route("/new", name="toernooi_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
	    if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    $toernooi = new Toernooi();
		    $form     = $this->createForm( ToernooiType::class, $toernooi );
		    $form->handleRequest( $request );

		    if ( $form->isSubmitted() && $form->isValid() ) {
			    $entityManager = $this->getDoctrine()->getManager();
			    $entityManager->persist( $toernooi );
			    $entityManager->flush();

			    return $this->redirectToRoute( 'toernooi_index' );
		    }

		    return $this->render( 'toernooi/new.html.twig', [
			    'toernooi' => $toernooi,
			    'form'     => $form->createView(),
		    ] );
	    }
	    else{
	    	return $this->render('default/accessdenied.html.twig');
	    }
    }

    /**
     * @Route("/{id}", name="toernooi_show", methods={"GET"})
     */
    public function show(Toernooi $toernooi): Response
    {
	    if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    return $this->render( 'toernooi/show.html.twig', [
			    'toernooi' => $toernooi,
		    ] );
	    }
	    else{
	    	return $this->render('default/accessdenied.html.twig');
	    }
    }

    /**
     * @Route("/{id}/edit", name="toernooi_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Toernooi $toernooi): Response
    {
	    if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    $form = $this->createForm( ToernooiType::class, $toernooi );
		    $form->handleRequest( $request );

		    if ( $form->isSubmitted() && $form->isValid() ) {
			    $this->getDoctrine()->getManager()->flush();

			    return $this->redirectToRoute( 'toernooi_index', [
				    'id' => $toernooi->getId(),
			    ] );
		    }

		    return $this->render( 'toernooi/edit.html.twig', [
			    'toernooi' => $toernooi,
			    'form'     => $form->createView(),
		    ] );
	    }
	    else{
	    	return $this->render('default/accessdenied.html.twig');
	    }
    }

    /**
     * @Route("/{id}", name="toernooi_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Toernooi $toernooi): Response
    {
	    if($this->isGranted("ROLE_SUPER_ADMIN")) {
		    if ( $this->isCsrfTokenValid( 'delete' . $toernooi->getId(), $request->request->get( '_token' ) ) ) {
			    $entityManager = $this->getDoctrine()->getManager();
			    $entityManager->remove( $toernooi );
			    $entityManager->flush();
		    }

		    return $this->redirectToRoute( 'toernooi_index' );
	    }
	    else{
	    	return $this->render('default/accessdenied.html.twig');
	    }
    }
}
