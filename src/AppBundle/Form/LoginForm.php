<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginForm extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'username', TextType::class, [ 'required' => true ] )
			->add( 'password', PasswordType::class, [ 'required' => true ] );
		/*	->add( 'remember_me', CheckboxType::class, [ 'required' => false ] );*/
	}


	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults( array(

			                        'data_class'         => 'AppBundle\Entity\User',
			                        'is_edit'            => false,
			                        'allow_extra_fields' => true,
		                        ) );
	}

	public function getName() {
		return 'LoginForm';
	}

}