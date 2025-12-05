<?php

namespace App\Form\Type\Step;

use App\Entity\Candidate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DispoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('available_now', CheckboxType::class, [
                'required' => false, 
                'label' => 'Disponible immédiatement?',
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event)  {
            $data = $event->getData();
            $form = $event->getForm();
            $availableNow = empty($data['available_now']);

            if ($availableNow) {
                $data['availability_date'] = null;
                $event->setData($data);
                $form->add('availability_date', DateType::class, [
                    'widget' => 'single_text',
                    'required' => false,
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
            'inherit_data' => true,
        ]);
    }
}
