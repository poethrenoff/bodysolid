<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Product;

class ProductVideoAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $parentAssociationMapping = 'product';

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
            ->add('product', EntityType::class, ['class' => Product::class, 'label' => 'Товар'])
            ->add('video', TextareaType::class, ['label' => 'Видео'])
            ->add('sort', IntegerType::class, ['label' => 'Порядок']);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, ['label' => 'ID'])
            ->add('product', null, ['label' => 'Товар'])
            ->addIdentifier('video', null, ['label' => 'Видео'])
            ->add('sort', null, ['label' => 'Порядок', 'editable' => true])
        ;
    }
}