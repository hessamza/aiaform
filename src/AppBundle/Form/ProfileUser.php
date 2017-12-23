<?php
namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class ProfileUser extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
            ->add('gender', ChoiceType::class, array(
                'choices'  => array(
                    'زن' => '0',
                    'مرد' => '1'
                ),
            ))
            ->add( 'phone', TextType::class, [ 'required' => true ] )
            ->add( 'mobile', TextType::class, [ 'required' => true ] );
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(
			array(
				'data_class'         => 'AppBundle\Entity\User',
                "validation_groups"=>"register",
				'allow_extra_fields' => true));
	}

	public function getName() {
		return 'ProfileForm';
	}

}