<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Quotes;
use App\Entity\Services;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuotesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('email', TextType::class)
            ->add('phoneNumber', TextType::class)
            ->add('message', TextareaType::class)
            // ->add('isRead')
            ->add('categories', EntityType::class,[
                'class'=> 'App:Categories',
                'choice_label'=>'name',
            ])
            ->add('services', EntityType::class, [
                'class'=> 'App:Services',
                'choice_label'=>'name',
                
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
