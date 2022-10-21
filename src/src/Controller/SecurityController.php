<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class SecurityController extends AbstractController
{
    //Commençons par créer notre route dans un nouveau SecurityController
    #[Route('/login', name: "app_login")]
    public function login(): Response
    {
        return $this->render(
            '/security/login.html.twig',
            [
                "controller_name" => 'SecurityController'
            ]
        );
    }
}