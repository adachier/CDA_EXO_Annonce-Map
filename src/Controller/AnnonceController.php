<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\AnnonceListByUser;
use App\Form\AnnonceType;
use App\Repository\AnnonceListByUserRepository;
use App\Repository\AnnonceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/annonce')]
class AnnonceController extends AbstractController
{
    #[Route('/', name: 'app_annonce_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, AnnonceRepository $annonceRepository, SluggerInterface $slugger): Response
    {    
        $author = $this->getUser();
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imgfile')->getData();

            if($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $annonce->setImgfile($newFilename);
            }
            $annonce->setAuthor($author);
            $annonce->setIsVisible(true);
            $annonceRepository->add($annonce);
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_show', methods: ['GET'])]
    public function show($id, Annonce $annonce, AnnonceRepository $annonceRepo): Response
    {
        $thisAnnonce = $annonceRepo->find($id);

        if($thisAnnonce->getisVisible() == false) {
            $this->addFlash('Erreur', 'Cette annonce n\'existe plus');
            return $this->redirectToRoute('home');
        } 

        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit($id, Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        $thisAnnonce = $annonceRepository->find($id);

        if($thisAnnonce->getisVisible() == false) {
            $this->addFlash('Erreur', 'Cette annonce n\'existe plus');
            return $this->redirectToRoute('home');
        } 
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        $author = $this->getUser();
        if($author == false){
            $this->addFlash('Erreur', 'Vous devez avoir un compte pour ajouter une annonce');
            return $this->redirectToRoute('home');
        }

        if($author->getRoles() == 'ROLE_ADMIN' or $annonce->getAuthor()==$author) {
            if($form->isSubmitted() && $form->isValid()) {
                $annonceRepository->add($annonce);
                $this->addFlash('Succès', 'Votre annonce a bien été modifiée');
                return $this->redirectToRoute('home',[], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('annonce/edit.html.twig', [
                'annonce' => $annonce,
                'form' => $form,
            ]);
        }
        $this->addFlash('Erreur', 'Vous devez avoir un compte pour ajouter/éditer une annonce');
        return $this->redirectToRoute('home');
    }

    #[Route('/{id}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        $author = $this->getUser();
        if($author == false){
            $this->addFlash('Erreur', 'Votre devez avoir un compte pour ajouter une annonce');
            return $this->redirectToRoute('home');
        }
        if ($author !== $annonce->getAuthor()) {
            $this->addFlash('Erreur', 'Vous n\'êtes pas l\'auteur de cette annonce');
            return $this->redirectToRoute('home');
        }
        if ($author == $annonce->getAuthor()) {
            $annonce->setisVisible(false);
            $annonceRepository->add($annonce);
        }else {
            $this->addFlash('Erreur', "Vous n'êtes pas l'auteur de cette annonce !" );
            return $this->redirectToRoute('app_annonce_show', ['id' => $annonce->getId()]);
        }
        $this->addFlash('Succès', "Votre événement a bien été supprimé !" );

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/fav', name: 'app_annonce_fav', methods: ['GET', 'POST'])]
    public function favUserAnnonce(Annonce $annonce, AnnonceListByUserRepository $annonceByUserRepo): Response
    {
        $user = $this->getUser();

        if(!$user) return $this->redirectToRoute('app_login');

        if($annonce->isUserfav($user)){
            $signedUp = $annonceByUserRepo->findOneBy([
                'annonces' => $annonce,
                'users' => $user
            ]);

            $annonceByUserRepo ->remove($signedUp);
            $this->addFlash('Erreur', "Cette annonce n'est plus dans vos favoris" );

            return $this->redirectToRoute('home');
        }

        $newFav = new AnnonceListByUser();
        $newFav->setAnnonces($annonce)
        ->setUsers($user);

        $annonceByUserRepo->add($newFav);
        $this->addFlash('Succès', "Cette annonce est désormais dans vos favoris" );

        // return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        return $this->redirectToRoute('home');
    }
}
