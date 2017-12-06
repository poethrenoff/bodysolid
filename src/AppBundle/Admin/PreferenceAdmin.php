<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Site;

class PreferenceAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('site', EntityType::class, ['class' => Site::class, 'label' => 'Сайт', 'required' => false])
            ->add('name', TextType::class, ['label' => 'Имя'])
            ->add('title', TextType::class, ['label' => 'Название'])
            ->add('value', TextType::class, ['label' => 'Значение']);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('site', null, ['label' => 'Сайт']);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, ['label' => 'ID'])
            ->add('site', null, ['label' => 'Сайт'])
            ->add('name', null, ['label' => 'Имя'])
            ->addIdentifier('title', null, ['label' => 'Название'])
            ->add('value', null, ['label' => 'Значение']);
    }
}