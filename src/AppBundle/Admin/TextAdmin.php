<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Site;

class TextAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('site', EntityType::class, ['class' => Site::class, 'label' => 'Сайт', 'required' => false])
            ->add('name', TextType::class, ['label' => 'Ссылка', 'required' => false])
            ->add('title', TextType::class, ['label' => 'Название'])
            ->add('text', TextareaType::class, ['label' => 'Текст', 'attr' => ['class' => 'editor']]);
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
            ->add('name', null, ['label' => 'Ссылка'])
            ->addIdentifier('title', null, ['label' => 'Название']);
    }
}