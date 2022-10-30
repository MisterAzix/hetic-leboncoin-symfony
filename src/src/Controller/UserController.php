<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vote;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\LoginFormAuthenticator;

#[Route('/user')]
class UserController extends AbstractController
{
    private $passwordHasher;
    private $security;

    public function __construct(UserPasswordHasherInterface $passwordHasher, Security $security)
    {
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        UserAuthenticatorInterface $authenticator,
        LoginFormAuthenticator $formAuthenticator
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($this->passwordHasher->hashPassword($user, $form->getData()->getPassword()))
                ->setCreateDate(new \DateTime());
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'The registration is successfull');

            return $authenticator->authenticateUser(
                $user,
                $formAuthenticator,
                $request
            );
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, Request $request, $id): Response
    {
        if (!$user) {
            return $this->redirectToRoute('app_error');
        }

        if (strval($this->getUser()->getId()) !== $id && !$this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, $id): Response
    {
        if (!$user) {
            return $this->redirectToRoute('app_error');
        }

        if (strval($this->getUser()->getId()) !== $id && !$this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirect($request->headers->get('referer'));
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository, $id): Response
    {
        if (!$user) {
            return $this->redirectToRoute('app_error');
        }

        if (strval($this->getUser()->getId()) !== $id && !$this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirect($request->headers->get('referer'));
        }

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/vote', name: "app_user_vote", methods: ['GET', 'POST'])]
    public function userVote(User $user, Request $request, EntityManagerInterface $entityManager,  VoteRepository $voteRepository): Response
    {
        $formUserID = $this->getUser()->getId();
        $toUserId = $user->getId();
        $vote = new Vote();

        if ($request->query->get('direction')) {
            $direction = $request->query->get('direction');
            if (!$voteRepository->hasVote($formUserID, $toUserId, $direction)) {
                $vote->setFromUserId($formUserID)->setToUserId($toUserId);
                $direction == 'up' ? $user->upVote() : $user->upDown();
                $voteRepository->upDateVote($formUserID, $toUserId, $direction);
                $vote->setDirection($direction);
                $entityManager->persist($vote);
                $entityManager->flush();
            }
        }

        return $this->render('user/vote.html.twig', [
            'user' => $user
        ]);
    }
}
