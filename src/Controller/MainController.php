<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Repository\AnnonceRepository;
use App\Repository\MarqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(AnnonceRepository $annonceRepository, MarqueRepository $marqueRepo): Response
    {
        return $this->render('main/index.html.twig', [
            'annonces' => $annonceRepository->findBy([
                'isVisible' => true
            ]),
            'marques' => $marqueRepo->findAll(),
        ]);
    }

    #[Route('/marque/{id}', name: 'tabFilter')]
    public function tab(Marque $mark, AnnonceRepository $annonceRepo, MarqueRepository $marqueRepo): Response
    {
        $annonces = $annonceRepo->findAll();
        return $this->render('main/tabByMark.html.twig', [
            'mark' => $mark,
            'marques' => $marqueRepo->findAll(),
            'annonces' => $annonces,
        ]);
    }
}
