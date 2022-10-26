<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
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

    #[Route('/popular', name: 'app_ad_popular')]
    public function mostPopular(AdRepository $adRepository, Request $request)
    {
        $search = $request->request->get('q');
        $popularAds = $adRepository->findMostPopular($search);

        return $this->render('ad/popular.html.twig', [
            'ads' => $popularAds
        ]);
    }

    #[Route('/new', name: 'app_ad_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AdRepository $adRepository, UploadHelper $helper): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ad->setUser($this->getUser());

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

    #[Route('/{id}', name: 'app_ad_show', methods: ['GET'])]
    public function show(Ad $ad): Response
    {
        return $this->render('ad/show.html.twig', [
            'ad' => $ad,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ad_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ad $ad, AdRepository $adRepository, UploadHelper $helper): Response
    {
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
        if ($this->isCsrfTokenValid('delete'.$ad->getId(), $request->request->get('_token'))) {
            $adRepository->remove($ad, true);
        }

        return $this->redirectToRoute('app_ad_index', [], Response::HTTP_SEE_OTHER);
    }
}
