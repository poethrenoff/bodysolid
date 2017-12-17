<?php
namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PurchaseAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $datagridValues = [
        '_sort_by' => 'date',
        '_sort_order' => 'desc',
    ];

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('person', TextType::class, ['label' => 'ФИО'])
            ->add('email', TextType::class, ['label' => 'Email'])
            ->add('phone', TextType::class, ['label' => 'Телефон'])
            ->add('address', TextareaType::class, ['label' => 'Адрес'])
            ->add('comment', TextareaType::class, ['label' => 'Комментарий', 'required' => false])
            ->add('date', DateTimeType::class, ['label' => 'Дата'])
            ->add('sum', NumberType::class, ['label' => 'Сумма'])
            ->add('status', ChoiceType::class, ['label' => 'Статус', 'choices' => [
                'Новый' => 'new',
                'Подтвержден' => 'confirm',
                'В доставке' => 'deliver',
                'Выполнен' => 'complete',
                'Отменен' => 'cancel',
            ]]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('person', null, ['label' => 'ФИО'])
            ->add('email', null, ['label' => 'Email'])
            ->add('status', 'doctrine_orm_choice', ['label' => 'Статус'], ChoiceType::class, ['choices' => [
                'Новый' => 'new',
                'Подтвержден' => 'confirm',
                'В доставке' => 'deliver',
                'Выполнен' => 'complete',
                'Отменен' => 'cancel',
            ]]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, ['label' => 'ID'])
            ->add('person', null, ['label' => 'ФИО'])
            ->add('email', null, ['label' => 'Email'])
            ->add('date', null, ['label' => 'Дата'])
            ->add('sum', null, ['label' => 'Сумма', 'editable' => true])
            ->add('status', 'choice', ['label' => 'Статус', 'choices' => [
                'new' => 'Новый',
                'confirm' => 'Подтвержден',
                'deliver' => 'В доставке',
                'complete' => 'Выполнен',
                'cancel' => 'Отменен',
            ], 'editable' => true])
            ->add('_action', 'actions', [
                'label' => 'Операции',
                'actions' => [
                    'edit' => [],
                    'product_item' => ['template' => '@App/Admin/purchase_item.html.twig'],
                ]]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create')->remove('delete');
    }
}