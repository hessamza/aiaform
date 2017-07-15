<?php

namespace AppBundle\Form;

use AppBundle\Entity\ServiceItems;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('companyName')
            ->add('userName')
            ->add('recharge', ChoiceType::class, array(
                'choices'  => array(
                    '' => null,
                    'تمدید ماه' => 'lastMonth',
                    'تمدید گذشته' => 'ago',
                ),
            ))
            ->add('register', ChoiceType::class, array(
                'choices'  => array(
                    '' => null,
                    'رجیستری ماه' => 'lastMonth',
                    'رجیستری گذشته' => 'ago',
                ),
            ))
            ->add('phone', ChoiceType::class, array(
                'choices'  => array(
                    '' => null,
                    'تلفنی ماه' => 'lastMonth',
                    'تلفنی گذشته' => 'ago',
                ),
            ))
            ->add('telegram', ChoiceType::class, array(
                'choices'  => array(
                    '' => null,
                    'تلگرام ماه' => 'lastMonth',
                    'تلگرام گذشته' => 'ago',
                    'تبلیغات تلگرام'=>'advTelegram'
                ),
            ))
            ->add('direct', ChoiceType::class, array(
                'choices'  => array(
                    '' => null,
                    'نمایشگاه نفت و گاز تهران ۹۶' => 'oil96',
                ),
            ))
//            ->add('sharingMethods', EntityType::class, [
//                'class' => 'AppBundle\Entity\Sharing','choice_label' => 'text',
//            ])
            ->add('shareItems', EntityType::class, array(
                'class' => 'AppBundle\Entity\ShareItems',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ))
            ->add('serviceItems', EntityType::class, array(
                'class' => 'AppBundle\Entity\ServiceItems',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ))
          //  ->add("serviceItems",ServiceItemsType::class)
           ->add('separate', ChoiceType::class, array(
                'choices'  => array(
                    '' => null,
                    'سراسری' => 'global',
                    'استانی' => 'local',
                    'تخصصی'=>'professional',
                    'استانی تخصصی'=>'local-professional'
                ),
            ))
            ->add('contractPrice')
            ->add('discount')
            ->add('basePrice');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contract',

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_contract';
    }


}
