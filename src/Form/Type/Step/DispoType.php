<?php

namespace App\Form\Type\Step;

use App\Form\Data\BasicDto;
use App\Entity\Candidate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DispoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('availability_date', DateType::class, [
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('Disponible_immediatement', CheckboxType::class, [
                'mapped' => false,
                'required' => false, 
                'label' => 'Disponible immédiatement?',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
            'inherit_data' => true,
        ]);
    }
}
