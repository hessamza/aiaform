<?php

namespace AppBundle\Form;

use AppBundle\Entity\ServiceItems;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('contractMan')
            ->add('contractType', ChoiceType::class, array(
                'choices'  => array(
                    '' => null,
                    'تمدید' => 'recharge',
                    'رجیستری' => 'register',
                    'تلفنی' => 'phone',
                    'تلگرام' => 'telegram',
                    'مستقیم' => 'direct',
                    'نمایشگاهی' => 'exhibition',
                    'تبلیغات' => 'adv',
                ),
            ))
            ->add('contractTime', ChoiceType::class, array(
                'choices'  => array(
                    '' => null,
                    '۶ماه' => '6month',
                    '۱۲ماه' => '12month'
                ),
            ))
            ->add(
                'contractDate', DateType::class, [
                'widget'   => 'single_text',
                'format'   => 'dd-MM-yyyy',
                'attr'=>array('style'=>'display:none;'),
                'required' => false,
                    'invalid_message'=>'تاریخ وارد شده درست نیست'
            ])
            ->add(
                'contractStartDate', DateType::class, [
                'widget'   => 'single_text',
                'format'   => 'dd-MM-yyyy',
                'attr'=>array('style'=>'display:none;'),
                'required' => false,
                    'invalid_message'=>'تاریخ وارد شده درست نیست'
            ])
            ->add(
                'contractEndDate', DateType::class, [
                'widget'   => 'single_text',
                'format'   => 'dd-MM-yyyy',
                'attr'=>array('style'=>'display:none;'),
                'required' => false,
                    'invalid_message'=>'تاریخ وارد شده درست نیست'
            ])
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
            ->add('exhibition', ChoiceType::class, array(
                'choices'  => array(
                    '' => null,
                    'نمایشگاه نفت و گاز تهران ۹۶' => 'oil96',
                ),
            ))
            ->add('adv', ChoiceType::class, array(
                'choices'  => array(
                    '' => null,
                    'سایت' => 'site',
                    'ایمیل' => 'email',
                    'تلگرام' => 'telegram',
                    'sms' => 'sms',
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
           ->add('separate', ChoiceType::class, array(                'choices'  => array(
                    '' => null,
                    'سراسری' => 'global',
                    'استانی' => 'local',
                    'تخصصی'=>'professional',
                    'استانی تخصصی'=>'local-professional'
                ),
            ))
            ->add('contractPrice')
            ->add('discount',TextType::class,[
            ])
            ->add('basePrice')
            ->add('customerPhone')
            ->add('customerAddress')
            ->add('haveExtraContractPrice')
            ->add('extraContractPrice')
            ->add('extraDescription')
            ->add('items')
            ->add('posts', EntityType::class, array(
                'class' => 'AppBundle\Entity\Posts',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ))
            ->add('itemDescription');

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
