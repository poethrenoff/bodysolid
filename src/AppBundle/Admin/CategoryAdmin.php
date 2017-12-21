<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Category;

class CategoryAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $datagridValues = [
        '_sort_by' => 'sort',
        '_sort_order' => 'asc',
    ];

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('externalId', IntegerType::class, ['label' => 'Neotren ID', 'required' => false])
            ->add('category', EntityType::class, ['class' => Category::class, 'label' => 'Родительская категория', 'required' => false,])
            ->add('name', TextType::class, ['label' => 'Ссылка', 'required' => false])
            ->add('title', TextType::class, ['label' => 'Название'])
            ->add('shortTitle', TextType::class, ['label' => 'Кр. название'])
            ->add('discount', TextType::class, ['label' => 'Скидка', 'required' => false])
            ->add('sort', IntegerType::class, ['label' => 'Порядок'])
            ->add('active', CheckboxType::class, ['label' => 'Видимость', 'required' => false]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('category', null, array('label' => 'Родительская категория'))
            ->add('title', null, array('label' => 'Название'))
            ->add('active', null, array('label' => 'Видимость'));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, ['label' => 'ID'])
            ->add('category', null, ['label' => 'Родительская категория'])
            ->add('name', null, ['label' => 'Ссылка'])
            ->addIdentifier('title', null, ['label' => 'Название'])
            ->add('shortTitle', null, ['label' => 'Кр. название'])
            ->add('discount', 'integer', ['label' => 'Скидка', 'editable' => true])
            ->add('sort', null, ['label' => 'Порядок', 'editable' => true])
            ->add('active', null, ['label' => 'Видимость', 'editable' => true])
            ->add('_action', 'actions', [
                'label' => 'Операции',
                'actions' => [
                    'product' => array('template' => '@App/Admin/product.html.twig')
                ]]);
    }
}