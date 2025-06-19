<?php

namespace App\Controller;

use App\Repository\ProjectsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route ('/projet/{id}', name: 'app_show_project')]
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
}
