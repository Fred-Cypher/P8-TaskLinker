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

class ProjectController extends AbstractController{
    public function __construct(private ProjectsRepository $projectsRepository, private EntityManagerInterface $em)
    {
        
    }

    #[Route ('/', name: 'app_home')]
    public function index(): Response
    {
        $projects = $this->projectsRepository->findAll();

        return $this->render('home.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route ('/project/{id}', name: 'app_show_project')]
    public function show(int $id): Response
    {
        $project = $this->projectsRepository->find($id);

        if (!$project) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('projects/show.html.twig', [
            'project' => $project
        ]);
    }

    #[Route ('/newProject', name: 'app_new_project')]
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

            return $this->redirectToRoute('app_home');
        }

        return $this->render('projects/add.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }
}
