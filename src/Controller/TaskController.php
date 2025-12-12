<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\TaskService;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
 


#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'task_index')]
    public function index(TaskRepository $taskRepository): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $tasks = $taskRepository->findAll();
        } else {
            $tasks = $taskRepository->findBy(['author' => $user]);
            // $tasks = $taskRepository->findByUser($user);
        }

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/create', name: 'task_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $task = new Task();
        $task->setAuthor($this->getUser());

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'Tâche créée avec succès !');
            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'task_view', requirements: ['id' => '\d+'])]
    #[IsGranted('TASK_VIEW', 'task')]
    public function view(Task $task): Response
    {

        return $this->render('task/view.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'task_edit')]
    #[IsGranted('TASK_EDIT', 'task')]
    public function edit(Task $task, Request $request, EntityManagerInterface $em, TaskService $taskService): Response
    {
        if (!$taskService->canEdit($task)) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier cette tâche car elle a été créée il y a plus de 7 jours.');
            return $this->redirectToRoute('task_index');
         }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush(); // updatedAt est automatiquement mis à jour grâce à PreUpdate

            $this->addFlash('success', 'Tâche modifiée avec succès !');
            return $this->redirectToRoute('task_view', ['id' => $task->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'task_delete')]
    #[IsGranted('TASK_DELETE', 'task')]
    public function delete(Task $task, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('TASK_DELETE', $task)) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer cette tâche.');
            return $this->redirectToRoute('task_index');
        }

        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'Tâche supprimée.');
        return $this->redirectToRoute('task_index');
    }
}
