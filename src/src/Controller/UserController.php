<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\AppLoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UserController extends AbstractController
{

    #[Route('/user/new', name: "app_user_new")]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher,
    UserAuthenticatorInterface $authenticator, AppLoginAuthenticator $appLoginAuthenticator): Response
    {
        if ($request->isMethod('POST')) {
            if (!empty($request->request->get('password'))
                && !empty($request->request->get('password2'))
                && $request->request->get('password') === $request->request->get('password2')
                && $this->isCsrfTokenValid('create_form', $request->request->get('csrf'))) {

                $newUser = new User();
                $newUser->setName($request->request->get('name'))
                    ->setFirstName($request->request->get('firstName'))
                    ->setLogin($request->request->get('login'))
                    ->setPhone($request->request->get('phone'))
                    ->setEmail($request->request->get('email'))
                    ->setPassword($hasher->hashPassword($newUser, $request->request->get('password')))
                    ->setCreateDate(new \DateTime());

                $entityManager->persist($newUser);
                $entityManager->flush();

                return $authenticator->authenticateUser(
                    $newUser,
                    $appLoginAuthenticator,
                    $request
                );
            }
            return $this->render('user/user_new.html.twig', [
                'error' => 'Les deux mots de passe sont différents'
            ]);
        }
        return $this->render('user/user_new.html.twig');
    }

    #[Route('/user/{email}', name: 'app_user')]
    public function index(User $user): Response
    {
        return $this->render('user/index.html.twig', [
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
        $user->setName($request->request->get('name'))
            ->setFirstName($request->request->get('firstName'))
            ->setLogin($request->request->get('login'))
            ->setPhone($request->request->get('phone'))
            ->setEmail($request->request->get('email'))
            ->setPassword($request->request->get('password'));

        $entityManager->flush();

        return $this->redirectToRoute('app_user', [
            'email' => $user->getEmail()
        ]);
    }

    #[Route('/user/{email}/delete', name: 'app_user_delete_api')]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
