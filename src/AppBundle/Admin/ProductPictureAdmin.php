<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Product;

class ProductPictureAdmin extends AbstractAdmin
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
        $imageOptions = ['label' => 'Изображение (url)', 'required' => false];
        if (($subject = $this->getSubject()) && ($webPath = $subject->getImage())) {
            $imageOptions['help'] = '<img src="' . $webPath . '" style="max-width: 150px; max-height: 150px" />';
        }

        $formMapper
            ->add('product', EntityType::class, ['class' => Product::class, 'label' => 'Товар'])
            ->add('imageFile', FileType::class, ['label' => 'Изображение', 'required' => false])
            ->add('image', TextType::class, $imageOptions)
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
            ->addIdentifier('image', null, ['label' => 'Изображение'])
            ->add('sort', null, ['label' => 'Порядок', 'editable' => true])
        ;
    }
}