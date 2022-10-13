<?php
#un lieu abstrait conçu pour accueillir des ensembles de termes appartenant à un même répertoire
namespace App\Controller;
#Les méthodes de controller doivent retourner un objet de la classe « \Symfony\Component\HttpFoundation\ Response() »
use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    #autowire le repository comme un service pour ne plus avoir à appeler l’entity manager
    #[Route('/', name: "app_question_index")]
    public function index(QuestionRepository $repository): Response
    {
        //query
        $questions = $repository->findAll();
        //Utiliser la query personnalisée
        $questionsSort = $repository->findAllAskedOrderByNewest();
//        dd($questionsSort);
        return new Response('Hello word');
    }

    #[Route('/question', name: "app_question")]
    public function getQuestion($projectDir): Response
    {
        #debug died permet de debugger, donne des informations
        dd($projectDir);
        #return new Response('Hello word');
    }

    //La persistence de données dans la db
    //Les entity sont présentes automatiquement dans les controllers
    #[Route('/questions/new/{idQuestion}', name: "app_questions_new")]
    public function new(EntityManagerInterface $entityManager, $idQuestion): Response
    {
        $answers = [
            'je ne suis pas ..',
            'Je vais essayer',
            'Merci de me conacter',
        ];

//        //Créer un objet question
//        //Mon objet n’aura aucun « id » tant
//        //que je ne l’ai pas persisté en BDD,
//        //Doctrine le set automatiquement une
//        //fois le flush fait
//        $addQuestion = new Question();
//        //La syntaxe EOF, Notion multiligne pour rendre le code plus lisible
//        $addQuestion->setName('Justin')->setSlug('comme un nom')->setQuestion("Quel objet?");
//        if(rand(1, 10) > 2) {
//            $addQuestion->setAskedAt(new \DateTime(sprintf('%d days', rand(1, 108))));
//        }
//        //Utiliser un service Doctrine EntityManagerInterface pour la persistence
//        $entityManager->persist($addQuestion); //informe doctrine de l'objet semblable à : symfony console make: migration
//        $entityManager->flush();//fait la query : symfony console d:m:m

//        //Debug question object
//        dd($addQuestion);

        //Récupérer le repository en passant le nom de mon entity
        $repository = $entityManager->getRepository(Question::class);

        //Interroger le repository qui proposes plusieurs méthodes dont pour faire le crud
        $getQuestion = $repository->findOneBy(['id' => $idQuestion]);
        $getAllQuestion = $repository->findAll();
//      dump($getAllQuestion);
        //Trier de la date la plus récente à la plus vieille
        $getAllSortQuestion = $repository->findBy([], ['askedAt' => 'DESC']);
//      dump($getAllQuestion);

        //Faire une 404 quand on recoit un résultat null
        if(!$getQuestion) {
            throw $this->createNotFoundException("cette page n'existe pas");
            //rédireger l'utilisateur
        }
//        dd($getQuestion);
//        Utilisation de l’objet retourné par la db dans twig
//        return new Response('Une nouvelle question ajoutée !',   $addQuestion->getName(), $getQuestion->setQuestion());
        return $this->render(
            '/question/show.html.twig',
            [
                "question" => $getQuestion,
                "answers" => $answers
            ]
        );
    }
    #Question $question : PARAM CONVERTER fais le lien entre le nom de la wildcard passée en URL et le type hint de l’entité
    #Les wildcards dans les routes, permet de passer de param à nos routes
    #[Route('/question/{id}', name: "app_question_by_id")]
    public function getQuestionById(Question $question): Response
    {
        dd($question);
    }

    #Vue Twig
    #[Route('/questions/{slug}', name: "app_questions")]
    public function show($slug): Response
    {
        //idUniqque
        $id= md5("azdbzjdbfd");

        //Ajouter du markdown
        $question_text = "Ma pizza **me convient** ah merde";

//        $testService = $helper->getStrong($question_text );
//        $parsedQuestion = $helper->parse($question_text);

        #Prend deux params, Le chemin.le nom du template et Un array avec toutes les variables que l’on veut passer au template
        #On nomme le template comme la méthode du controller et on le range dans un dossier avec un nom explicite
        #Render va ultimement renvoyer un objet de la classe Response… mais avec notre template
        return $this->render(
            '/question/index.html.twig',
            [
                "questions" => sprintf('La question posée est : %s', $slug),
                "question_text" => $question_text,
                "id" => $id
            ]
        );
    }

    #[Route('/questions/{slug}/vote', name: "app_question_vote")]
    public function questionVote(Question $question, Request $request, EntityManagerInterface $entityManager):Response {
        $vote = $request->request-get('vote');
        $vote === 'up' ? $question->upVote() : $question->upDown();
//      Inutile de persist() pour un update
        $entityManager->flush();
//      dd($request->request->all());
        return $this->redirectToRoute('app_questions', [
            'slug' => $question->getSlug()
        ]);

    }

    //Delete question
    #[Route('/questions/{id}/delete', name: "app_question_delete")]
    public function questionDelete($id, EntityManagerInterface $entityManager):Response {
        $question = $entityManager->getReference(Question::class, $id);
        $entityManager->remove($question);
        $entityManager->flush();

        return $this->redirectToRoute('app_question_index');
    }


}
