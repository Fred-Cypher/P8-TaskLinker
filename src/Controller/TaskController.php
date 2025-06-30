<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Entity\Tasks;
use App\Form\TaskFormType;
use App\Repository\TasksRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    public function __construct(private TasksRepository $tasksRepository, private EntityManagerInterface $em)
    {
        
    }

    #[Route('project/{projectId}/newTask', name: 'app_new_task')]
    public function new(Request $request, int $projectId): Response
    {
        $project = $this->em->getRepository(Projects::class)->find($projectId);

        if(!$project) {
            throw $this->createNotFoundException('Projet introuvable');
        }

        $task = new Tasks;
        $task->setProject($project);

        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $task->setCreatedAt(new \DateTimeImmutable());
            $task->setUpdatedAt(new \DateTimeImmutable());
            //$task = $form->getData();

            $this->em->persist($task);
            $this->em->flush();

            return $this->redirectToRoute('app_show_project', ['id' => $projectId]);
        }

        return $this->render('tasks/add.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    #[Route('task/edit/{taskId}', name: 'app_edit_task', methods: ['GET', 'POST'])]
    public function editTask(Request $request, int $taskId): Response
    {
        $task = $this->em->getRepository(Tasks::class)->find($taskId);
        if (!$task){
            throw $this->createNotFoundException('Tâche introuvable');
        }

        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUpdatedAt(new DateTimeImmutable());

            $this->em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('tasks/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    #[Route('task/delete/{taskId}', name: 'app_delete_task', methods: ['GET','POST'])]
    public function deleteTask(Request $request, int $taskId): Response
    {
        $task = $this->em->getRepository(Tasks::class)->find($taskId);

        if (!$task){
            throw $this->createNotFoundException('Tâche introuvable');
        }

            $this->em->remove($task);
            $this->em->flush();

        return $this->redirectToRoute('app_home');
    }
}