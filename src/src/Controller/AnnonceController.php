<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    #[Route('/annonce/{id}', name: "app_annonce_index")]
    public function getAnnonceById($id): Response
    {
        $annonce = "Cece est l'id de mon annonce".$id;

        return $this->render(
            '/annonce/index.html.twig',
            [
                "id" => $id,
                "annonce" => $annonce
            ]
        );
    }

    //conditionner l'affichage de cette route en vérifiant si l'utilisateur est conencté
    #[Route('/annonce/form', name: "app_annonce_form")]
    public function getAnnonceForm($userId): Response
    {
        $user = "User connecté :".$userId;

        return $this->render(
            '/annonce/annonceForm.html.twig',
            [
                "userId" => $user,
            ]
        );
    }

    #[Route('/annonces', name: "app_annonces")]
    public function getAnnonces(): Response
    {
       $annonces = "Mes annonces 1, 2, 3";

        return $this->render(
            '/annonce/annonces.html.twig',
            [
                "annonces" => $annonces
            ]
        );
    }
}

