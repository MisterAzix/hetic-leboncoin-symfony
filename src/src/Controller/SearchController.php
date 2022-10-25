<?php

namespace App\Controller;

use App\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(): Response
    {
        $searchBar = $this->createForm(SearchFormType::class);

        return $this->render('search/index.html.twig', [
            'controller_name' => 'SeachController',
            'searchForm' => $searchBar->createView()
        ]);
    }
}
