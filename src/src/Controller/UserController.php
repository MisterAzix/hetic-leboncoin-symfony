<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use ContainerWy4cZx2\EntityManager_9a5be93;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use function MongoDB\Driver\Monitoring\removeSubscriber;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user_index')]
    public function index(UserRepository $repository): Response
    {

        $users = $repository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{email}', name: 'app_user_show')]
    public function show(User $user): Response
    {
        return $this->render('user/user_show.html.twig', [
            'user' => $user
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
            ->setEmail($request->request->get('email'))
            ->setPassword($request->request->get('password'));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user');
    }

    #[Route('/user/{email}/modify', name: 'app_user_modify')]
    public function modify(User $user): Response
    {
        return $this->render('user/user_modify.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/user/{email}/update', name: 'app_user_update_api', methods: 'POST')]
    public function update(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user->setFirstName($request->request->get('firstName'))
            ->setName($request->request->get('name'))
            ->setEmail($request->request->get('email'));

        $entityManager->flush();

        return $this->redirectToRoute('app_user_show', [
            'email' => $user->getEmail()
        ]);
    }

    #[Route('/user/{email}/delete', name: 'app_user_delete_api')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('/user');
    }
}
