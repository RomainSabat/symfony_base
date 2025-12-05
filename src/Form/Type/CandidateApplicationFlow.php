<?php

namespace App\Form\Type;

use App\Entity\Candidate;
use App\Form\Data\BasicDto;
use App\Form\Type\Step\ExperienceType;
use App\Form\Type\Step\InfoPersoType;
use App\Form\Type\Step\Step1Type;
use App\Form\Type\Step\Step2Type;
use App\Form\Type\Step\Step3Type;
use App\Form\Type\Step\ConsentType;
use App\Form\Type\Step\DispoType;
use Symfony\Component\Form\Flow\AbstractFlowType;
use Symfony\Component\Form\Flow\FormFlowBuilderInterface;
use Symfony\Component\Form\Flow\Type\NavigatorFlowType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateApplicationFlow extends AbstractFlowType
{
    public function buildFormFlow(FormFlowBuilderInterface $builder, array $options): void
    {
        $builder->addStep('step1', InfoPersoType::class);
        $builder->addStep('step2', ExperienceType::class, [], fn(Candidate $data) => !$data->hasExperience());
        $builder->addStep('step3', DispoType::class);
        $builder->addStep('step4', ConsentType::class);

        $builder->add('navigator', NavigatorFlowType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
            'step_property_path' => 'currentStep',
        ]);
    }
}
