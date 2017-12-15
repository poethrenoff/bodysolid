<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Site;

class ArticleAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('site', EntityType::class, ['class' => Site::class, 'label' => 'Сайт', 'required' => false])
            ->add('title', TextType::class, ['label' => 'Название'])
            ->add('announce', TextareaType::class, ['label' => 'Анонс'])
            ->add('text', TextareaType::class, ['label' => 'Текст', 'attr' => ['class' => 'editor']])
            ->add('active', CheckboxType::class, ['label' => 'Видимость', 'required' => false]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('site', null, ['label' => 'Сайт'])
            ->add('active', null, ['label' => 'Видимость']);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, ['label' => 'ID'])
            ->add('site', null, ['label' => 'Сайт'])
            ->addIdentifier('title', null, ['label' => 'Название'])
            ->add('active', null, ['label' => 'Видимость', 'editable' => true]);
    }
}