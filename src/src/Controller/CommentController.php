<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends  AbstractController
{

    #[Route('/comment/{id}/vote/{direction}', name: "app_comment_vote")]
    public function show($id, $direction): Response
    {
        if($direction === 'up') {
            $voteCount = rand(7, 50);
        } else {
            $voteCount = rand(0,5);
        }
        #renvoyer un json
        return $this->json(['votes' => $voteCount]);
    }

}