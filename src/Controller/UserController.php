<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserFormType;
use App\Repository\UsersRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private UsersRepository $usersRepository, private EntityManagerInterface $em)
    {

    }

    #[Route ('/users', name: 'app_users')]
    public function allUsers(): Response
    {
        $users = $this->usersRepository->findAll();

        return $this->render('users/list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route ('/user/edit/{userId}', name: 'app_edit_user', methods: ['GET', 'POST'])]
    public function editUser(Request $request, int $userId): Response
    {
        $user = $this->em->getRepository(Users::class)->find($userId);

        if (!$user){
            throw $this->createNotFoundException('Employé introuvable');
        }

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user->setUpdatedAt(new DateTimeImmutable());

            $this->em->flush();

            return $this->redirectToRoute('app_users');
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/delete/{userId}', name: 'app_delete_user', methods: ['GET', 'POST'])]
    public function deleteUser(int $userId): Response
    {
        $user = $this->em->getRepository(Users::class)->find($userId);

        if (!$user){
            throw $this->createNotFoundException('Employé introuvable');
        }

        foreach ($user->getTasks() as $task){
            $task->setUsers(null);
        }

        foreach ($user->getProjects() as $project){
            $project->removeUser($user);
        }

        $this->em->remove($user);
        $this->em->flush();

        return $this->redirectToRoute('app_users');
    }
}