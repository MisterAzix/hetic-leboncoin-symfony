<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    #[Route('/user', name: 'app_user_index')]
    public function index(UserRepository $repository): Response
    {
        $users = $repository->findAll();
        // dd($users);
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/new', name: 'app_user_new')]
    public function addUser(): Response
    {
        return $this->render('user/user_new.html.twig');
    }

    #[Route('/user/create', name: 'app_user_create_api', methods: 'POST')]
    public function createUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $user->setName($request->request->get('name'))
            ->setFirstName($request->request->get('firstName'))
            ->setEmail($request->request->get('email'))
            ->setPassword($hasher->hashPassword($user, $request->request->get('password')))
            ->setCreateDate(new \DateTime());

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    // #[isGranted('ROLE_ADMIN')]
    // #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")]
    #[Route('/user/{email}', name: 'app_user_show')]
    public function show(User $user): Response
    {
        // Vérifier le rôle de l'utilisateur
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //Croiser des conditions
        // if($this->getUser() == $user && !$this->isGranted('ROLE_ADMIN_ANSWER')){
        //     throw $this->createAccessDeniedException('no access !');
        // }

        return $this->render('user/user_show.html.twig', [
            'user' => $user
        ]);
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
            ->setEmail($request->request->get('email'))
            ->setPassword($request->request->get('password'));

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
