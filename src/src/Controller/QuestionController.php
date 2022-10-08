<?php
#un lieu abstrait conçu pour accueillir des ensembles de termes appartenant à un même répertoire
namespace App\Controller;
#Les méthodes de controller doivent retourner un objet de la classe « \Symfony\Component\HttpFoundation\ Response() »
use Symfony\Component\HttpFoundation\Response;
#Permet au controller de bénéficier des méthodes de rendu et d'autres utilitaires
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#Permet de lier les méthodes de controller à des routes
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #Symfony va lire les routes du début du fichier vers le bas du fichier et matcher dès la première correspondance venue.
    #mettre les routes de la plus spécifique vers la moins spécifique
    #Les annotations se mettent dans un commentaire PHPDoc
    #[Route('/', name: "app_index")]
    public function index(): Response
    {
        return new Response('Hello word');
    }

    #[Route('/question', name: "app_question")]
    public function getQuestion($projectDir): Response
    {
        #debug died permet de debugger, donne des informations
        dd($projectDir);
        #return new Response('Hello word');
    }

    #Les wildcards dans les routes, permet de passer de param à nos routes
    #[Route('/question/{id}', name: "app_question_by_id")]
    public function getQuestionById($id):Response
    {
        dd($id);
    }

    #Vue Twig
    #[Route('/questions/{ma_wildcard}', name: "app_questions")]
    public function show($ma_wildcard): Response
    {
        #Prend deux params, Le chemin/le nom du template et Un array avec toutes les variables que l’on veut passer au template
        #On nomme le template comme la méthode du controller et on le range dans un dossier avec un nom explicite
        #Render va ultimement renvoyer un objet de la classe Response… mais avec notre template
        return $this->render('question/index.html.twig',
            [
                "questions" => sprintf('La question posée est : %s', $ma_wildcard)
            ]);
    }




}