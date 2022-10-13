<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    //recherche une question par nom ou par slug et l’afficher dans une page « /search-question »
    #[Route('/search-question', name: "app_search-question")]
    public function search_question(QuestionRepository $questionRepository, Request $request) : Response{
        $search = $request->query->get('search');
        $questions = $questionRepository->searchByName($search);

        return $this->render(
            '/question/index.html.twig',
            [
                "questions" => $questions,
            ]
        );

    }
}