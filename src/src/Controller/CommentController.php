<?php

namespace App\Controller;
//Importer les services
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class CommentController extends  AbstractController
{
    #utiliser Twig comme un service « classique
    private $twig;

    public function __construct( Environment $twig )
    {
        $this->twig = $twig;
    }
    #[Route('/comment}', name: "app_comment_vote")]
    public function indexComment( ):Response
    {
       $res = $this->twig->render(
            'base.html.twig', array()
        );
       return new  Response($res);
    }

    #utiliser le service loggerInterface
    #[Route('/comment/{id}/vote/{direction}', name: "app_comment_vote")]
    public function show($id, $direction, LoggerInterface $logger): Response
    {
        #Cette méthode est ce qu’on appelle « autowiring »
        $logger->info('Coucou');

        if($direction === 'up') {
            $voteCount = rand(7, 50);
            $logger->info('Vote up');
        } else {
            $voteCount = rand(0,5);
            $logger->info('Vote Down');
        }
        #renvoyer un json
        return $this->json(['votes' => $voteCount]);
    }


}