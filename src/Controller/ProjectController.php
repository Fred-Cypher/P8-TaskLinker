<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Form\ProjectFormType;
use App\Repository\ProjectsRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProjectController extends AbstractController{
    public function __construct(private ProjectsRepository $projectsRepository, private EntityManagerInterface $em)
    {

    }

    #[Route ('/home', name: 'app_home')]
    public function index(ProjectsRepository $projectsRepository): Response
    {
        $user = $this->getUser();
        $projects = $projectsRepository->findProjectsForUser($user);

        return $this->render('home.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route ('/project/{id}', name: 'app_show_project')]
    #[IsGranted('PROJECTS_VIEW', subject: 'project')]
    public function show(Projects $project): Response
    {
        return $this->render('projects/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route ('/admin/newProject', name: 'app_new_project')]
    #[IsGranted('PROJECTS_CREATE')]
    public function new(Request $request): Response
    {
        $project = new Projects;

        $form = $this->createForm(ProjectFormType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $project->setCreatedAt(new DateTimeImmutable());
            $project->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($project);
            $this->em->flush();

            return $this->redirectToRoute('app_show_project', ['id' => $project->getId()]);
        }

        return $this->render('projects/add.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route ('/project/archive/{projectId}', name: 'app_archive_project')]
    public function archiveProject(int $projectId): Response
    {
        $project = $this->projectsRepository->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException('Projet introuvable');
        }

        $project->setIsArchived(true);
        $this->em->flush();

        return $this->redirectToRoute('app_home');
    }

    #[Route ('/admin/project/edit/{project}', name: 'app_edit_project')]
    #[IsGranted('PROJECTS_EDIT', subject: 'project')]
    public function editProject(Request $request, Projects $project): Response
    {
//        $project = $this->em->getRepository(Projects::class)->find($projectId);

        $form = $this->createForm(ProjectFormType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setUpdatedAt(new \DateTimeImmutable());

            $this->em->flush();

            return $this->redirectToRoute('app_show_project', [
                'id' => $project->getId()
            ]);
        }

        return $this->render('projects/edit.html.twig', [
            'form' => $form,
            'project' => $project,
        ]);
    }
}
