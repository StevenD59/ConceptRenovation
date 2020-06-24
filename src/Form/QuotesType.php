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
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
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
                'class'=> 'App\Entity\Categories',
                'placeholder' => 'Selectionner une catégorie',
                'choice_label'=>'name',
            ])
        ;


        $formModifier = function (FormInterface $form, Categories $categories = null) {
            $services = null === $categories ? [] : $categories->getServices();

            $form->add('services', EntityType::class, [
                'class' => 'App\Entity\Services',
                'placeholder' => 'Selectionner une service',
                'choices' => $services,
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();

                $formModifier($event->getForm(), $data->getCategories());
            }
        );
        
        $builder->get('categories')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $categories = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $categories);
            }
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quotes::class,
        ]);
    }
}
