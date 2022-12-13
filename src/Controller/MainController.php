<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Entity\Marque;
use App\Repository\AnnonceRepository;
use App\Repository\DepartementRepository;
use App\Repository\MarqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(AnnonceRepository $annonceRepository, MarqueRepository $marqueRepo, DepartementRepository $departementRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'annonces' => $annonceRepository->findBy([
                'isVisible' => true
            ]),
            'marques' => $marqueRepo->findAll(),
            'departements' => $departementRepository->findAll(),
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

    #[Route('/departement/{id}', name: 'event_departement')]
    public function dept(Departement $departement): Response
    {
        return $this->render('main/dept.html.twig', [
            'departement' => $departement
        ]);
    }
}
