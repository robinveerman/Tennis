<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class XmlController extends AbstractController {
	/**
	 * @Route("/xml", name="xml")
	 */
	public function index( Request $request ) {
		$form = $this->createFormBuilder()
		             ->add( 'file', FileType::class )
		             ->add( 'save', SubmitType::class )
		             ->getForm();

		$form->handleRequest( $request );;
		if ( $form->isSubmitted() && $form->isValid() ) {

			$form_data = $form->getData();

			// Haal path op
			$data = $form_data['file']->getPathname();

			// Openen en lezen
			$file = fopen( $data, "r" );
			$xml  = fread( $file, filesize( $data ) );

			dump( $xml );

			// Sluiten
			fclose( $file );

			// XML naar Object
			$result = simplexml_load_string( $xml );
			dump( $result );
		}

		return $this->render( 'xml/index.html.twig', [
			'controller_name' => 'XmlController',
			'form'            => $form->createView(),
		] );
	}
}
