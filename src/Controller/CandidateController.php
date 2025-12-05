<?php

namespace App\Controller;

use App\Entity\Candidate as EntityCandidate;
use App\Form\Data\BasicDto;
use App\Form\Data\Candidate;
use App\Form\Type\CandidateApplicationFlow;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Flow\FormFlowInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CandidateController extends AbstractController
{
    #[Route('/apply', name: 'candidate_apply')]
    public function __invoke(Request $request): Response
    {
        /** @var FormFlowInterface $flow */
        $flow = $this->createForm(CandidateApplicationFlow::class, new EntityCandidate())
            ->handleRequest($request);

        if ($flow->isSubmitted() && $flow->isValid() && $flow->isFinished()) {
            // do something with $flow->getData();

            $this->addFlash('success', 'Your form flow was successfully finished!');

            return $this->redirectToRoute('candidate_apply');
        }

        return $this->render('candidate/apply.html.twig', [
            'form' => $flow->getStepForm(),
        ]);
    }
}
