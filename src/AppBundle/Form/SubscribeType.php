<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\Subscribe;

class SubscribeType extends AbstractType
{
    public static $types = array(
        'wholesale' => 'оптовая',
        'retail' => 'розничная',
    );
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Subscribe::class,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('person', TextType::class)
            ->add('company', TextType::class)
            ->add('type', ChoiceType::class, array(
                'choices'  => array_merge(array('' => null), array_flip(self::$types))
            ))
            ->add('phone', TextType::class)
            ->add('url', TextType::class)
            ->add('send', SubmitType::class, array('label' => 'Подписаться'))
            ->getForm();
    }
}