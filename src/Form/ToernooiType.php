<?php

namespace App\Form;

use App\Entity\Toernooi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToernooiType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'omschrijving' )
			->add( 'datum' )
			->add( 'spelers', null, [ 'attr' => [ 'class' => 'js-example-basic-multiple' ] ] );
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => Toernooi::class,
		] );
	}
}
