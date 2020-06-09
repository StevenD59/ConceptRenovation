<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Quotes;
use App\Entity\Services;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuotesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('phoneNumber')
            ->add('message')
            // ->add('isRead')
            ->add('services', EntityType::class, [
                'class'=> Services::class,
                'choice_label'=>'name',
                'expanded'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quotes::class,
        ]);
    }
}
