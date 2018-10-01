<?php
/**
 * Created by PhpStorm.
 * User: volodya
 * Date: 23.09.18
 * Time: 19:05
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType;


class ProfileType extends AbstractType
{
    public function getParent()
    {
        return ProfileFormType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('firstName', null, ['label' => 'label.firstName'])
            ->add('lastName', null, ['label' => 'label.lastName'])
            ->add('adress', null, ['label' => 'label.adress'])
            ->remove('username');

    }
}