<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AdType;
use App\Form\AnswerType;
use App\Form\QuestionType;
use App\Repository\AdRepository;
use App\Service\UploadHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ad')]
class AdController extends AbstractController
{
    #[Route('/', name: 'app_ad_index', methods: ['GET'])]
    public function index(AdRepository $adRepository): Response
    {
        return $this->render('ad/index.html.twig', [
            'ads' => $adRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ad_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AdRepository $adRepository, UploadHelper $helper): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ad->setUser($this->getUser())->setCreatedAt(new \DateTime());

            $newFiles = $form['thumbnailsUrls']->getData();
            if (count($newFiles) > 0) {
                $filenames = [];
                foreach ($newFiles as $newFile) {
                    $filenames[] = $helper->uploadAdImage($newFile);
                }
                $ad->setThumbnailsUrls($filenames);
            }

            $adRepository->save($ad, true);

            return $this->redirectToRoute('app_ad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ad/new.html.twig', [
            'ad' => $ad,
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'app_ad_search')]
    public function search(
        Request $request,
        AdRepository $adRepository
    ): Response {
        $search = $request->query->get('s');
        $ad = $adRepository->searchAds($search);

        return $this->render('ad/index.html.twig', [
            'ads' => $ad,
        ]);
    }

    #[Route('/{id}', name: 'app_ad_show', methods: ['GET'])]
    public function show(Ad $ad, AdRepository $adRepository, $id): Response
    {
        $existingAd = $adRepository->findOneBy(['id' => $id]);

        if (!$existingAd) {
            return $this->redirectToRoute('app_error');
        }

        $question = new Question();
        $answer = new Answer();
        $question_form = $this->createForm(QuestionType::class, $question, [
            'action' => $this->generateUrl('app_question_new', ['ad' => $ad->getId()]),
        ]);

        $questions = $ad->getQuestions();
        $answer_forms = [];
        foreach ($questions as $ques) {
            $form = $this->createForm(AnswerType::class, $answer, [
                'action' => $this->generateUrl('app_answer_new', ['question' => $ques->getId()]),
            ]);
            $answer_forms[$ques->getId()] = $form->createView();
        }

        return $this->render('ad/show.html.twig', [
            'ad' => $ad,
            'question_form' => $question_form->createView(),
            'answer_forms' => $answer_forms,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ad_edit', methods: ['GET', 'POST'])]
    public function edit($id, Request $request, Ad $ad, AdRepository $adRepository, UploadHelper $helper): Response
    {
        $existingAd = $adRepository->findOneBy(['id' => $id]);

        if (!$existingAd) {
            return $this->redirectToRoute('app_error');
        }

        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newFiles = $form['thumbnailsUrls']->getData();
            if (count($newFiles) > 0) {
                $filenames = [];
                foreach ($newFiles as $newFile) {
                    $filenames[] = $helper->uploadAdImage($newFile);
                }
                $ad->setThumbnailsUrls($filenames);
            }

            $adRepository->save($ad, true);

            return $this->redirectToRoute('app_ad_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ad_delete', methods: ['POST'])]
    public function delete(Request $request, Ad $ad, AdRepository $adRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ad->getId(), $request->request->get('_token'))) {
            $adRepository->remove($ad, true);
        }

        return $this->redirectToRoute('app_ad_index', [], Response::HTTP_SEE_OTHER);
    }
}
