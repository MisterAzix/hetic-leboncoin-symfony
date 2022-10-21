<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Tag;
use App\Factory\QuestionFactory;
use App\Factory\UserFactory;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    #[Route('/answers/show', name: "app_answers_index")]
    public function index(AnswerRepository $answerRepository, EntityManagerInterface $entityManager ): Response
    {
        $answers = $answerRepository->findAll();
         //ManyToOne = OneToMany
         $question = QuestionFactory::createOne();
         $answer = new Answer();
         $answer->setContent('du contenu')->setUser(UserFactory::createOne());

        //ManyToMAny
        //Question et tag
        $questionWithTag = QuestionFactory::createOne()->object();
        $tag1 = new Tag();
        $tag1->setName('Poneys');
        $tag2 = new Tag();
        $tag2->setName('Francis');

        $questionWithTag->addTag($tag1);
        $questionWithTag->addTag($tag2);

        $entityManager->persist($tag1);
        $entityManager->persist($tag2);
        $entityManager->flush();
       

        //Ajouter une question à une réponse 
         $question->addAnswer(($answer));
        //Fonctionne tout aussi bien que ca 
         $answer->setQuestion($question);

        return $this->render(
            '/answer/index.html.twig',
            [
                "answers" => $answers,
                "questions" => $questionWithTag
            ]
        );
    }

    /**Jointure et recherche */
    #[Route('/answers/popular', name: "app_answers_popular")]
    public function mostPopular(AnswerRepository $answerRepository, Request $request): Response
    {
        //A rajouter dans les param 
        // PaginatorInterface $paginator

        //Recherche
        $search = $request->query->get('q');
        //Fonctionnalité
        $popularAnswers = $answerRepository->findMostPopular($search);

        // Paginattion
        // $pagination = $paginator->paginate(
        //     $popularAnswers,
        //     $request->query->getInt('page', 1),
        //     10
        // );


        return $this->render(
            '/answer/popular.html.twig',
            [
                "answers" => $popularAnswers
                // "answers" => $pagination
            ]
        );
    }

    #[Route('/answers/{id}/vote', name: "app_answer_vote", methods: 'POST')]
    public function questionVote(Answer $answer, Request $request, EntityManagerInterface $entityManager): Response
    {
        $direction = $request->request->get('direction');
        $direction === 'up' ?  $answer->upVote() : $answer->upDown();
        $entityManager->flush();
        // dump($answer->getVotes());
        return $this->json([
            'votes' => $answer->getVotes()
        ]);
    }
}
