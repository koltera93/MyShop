<?php
/**
 * Created by PhpStorm.
 * User: volodya
 * Date: 10.09.18
 * Time: 20:12
 */
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;


class CategoriesAdmin extends AbstractAdmin
{

        protected function ConfigureListFields(ListMapper $list)
    {
    $list
    ->addIdentifier('id')
    ->addIdentifier('name')
    ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
    $filter
        ->add('id')
        ->add('name')
    ;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name')
            ->add('images', CollectionType::class,[
                'by_reference' => false],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                ]
            )
        ;
    }
}

