<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Factory\QuestionFactory;
use App\Factory\UserFactory;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    #[Route('/answers/show', name: "app_answers_index")]
    public function index(AnswerRepository $answerRepository,  QuestionFactory $questionFactory): Response
    {
        $answers = $answerRepository->findAll();

        //ManyToOne = OneToMany
        $question = QuestionFactory::createOne();
        $answer = new Answer();
        $answer->setContent('du contenu')->setUser(UserFactory::createOne());

        //Ajouter une question à une réponse 
        $question->addAnswer(($answer));
        //Fonctionne tout aussi bien que ca 
        $answer->setQuestion($question);

        return $this->render(
            '/answer/index.html.twig',
            [
                "answers" => $answers
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
