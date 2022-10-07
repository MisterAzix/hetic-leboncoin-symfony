<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController
{
    /**
     * @Route ("/annonce")
     * @return Response
     */
    public function annoncepage() {
        return new Response('Coucou');
    }
}