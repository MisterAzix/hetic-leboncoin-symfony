<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(AdRepository $adRepository): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'AppController',
            'ads' => array_slice($adRepository->findAll(), 0, 4),
        ]);
    }
}
