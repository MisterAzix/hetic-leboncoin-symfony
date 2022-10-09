<?php

namespace App\Controller;

use App\Entity\User;
use ContainerWy4cZx2\EntityManager_9a5be93;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/new', name: "app_user_new")]
    public function new(): Response
    {
        return $this->render('user/user_new.html.twig');
    }

    #[Route('/user/create', name: 'app_user_create_api', methods: 'POST')]
    public function createUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setName($request->request->get('name'))
            ->setFirstName($request->request->get('firstName'))
            ->setMail($request->request->get('email'))
            ->setPassword($request->request->get('password'));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user');
    }
}
