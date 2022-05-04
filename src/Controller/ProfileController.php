<?php

namespace App\Controller;

use App\Entity\AnnonceListByUser;
use App\Repository\AnnonceListByUserRepository;
use App\Repository\AnnonceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile')]
    public function index(UserRepository $userRepo, AnnonceListByUserRepository $annonceListByUserRepo, AnnonceRepository $annonceRepo): Response
    {
        $user = $this->getUser();
        return $this->render('profile/index.html.twig', [
            'annoncesFiltered' => $annonceListByUserRepo->findBy([
                'users' => $user]),
            'annonces' => $annonceRepo->findBy([
                'author' => $user,
            ]),
        ]);
    }
}
