<?php

namespace App\Form;

use App\Entity\SongsList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class SongsListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
       
            ->add('song')
        ;
        
        // Modify the form data before it is submitted
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $songsList = $event->getData();
            $songsList->setQuantity(0);
            $event->setData($songsList);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SongsList::class,
        ]);
    }
}
