<?php
/**
 * Created by PhpStorm.
 * User: volodya
 * Date: 17.09.18
 * Time: 14:12
 */

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;


class FeedbackAdmin extends AbstractAdmin
{

    protected function ConfigureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->addIdentifier('email')
            ->addIdentifier('message')
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
        $form->add('name');
        $form->add('email');
        $form->add('message');
    }
}