<?php

namespace App\Form;

use App\Entity\Wedstrijd;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WedstrijdType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'score1' )
			->add( 'score2' )
			->add( 'ronde' )
			->add( 'toernooiID', null, [ 'label' => 'Toernooi' ] )
			->add( 'speler1ID', null, [ 'label' => 'Speler 1' ] )
			->add( 'speler2ID', null, [ 'label' => 'Speler 2' ] )
			->add( 'winnaarID', null, [ 'label' => 'Winnaar' ] );
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( [
			'data_class' => Wedstrijd::class,
		] );
	}
}
