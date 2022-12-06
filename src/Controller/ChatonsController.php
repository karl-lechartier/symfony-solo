<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Form\ChatonSupprimerType;
use App\Form\ChatonType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatonsController extends AbstractController
{
    /**
     * @Route("/chatons/{idCategorie}", name="app_chatons_voir")
     */
    public function index($idCategorie, ManagerRegistry $doctrine): Response
    {
        $categorie = $doctrine->getRepository(Categorie::class)->find($idCategorie);
        //si on n'a rien trouvé -> 404
        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $idCategorie");
        }

        return $this->render('chatons/index.html.twig', [
            'categorie' => $categorie,
            "chatons" => $categorie->getChatons()
        ]);
    }

    /**
     * @Route("/chaton/ajouter/", name="app_chaton_ajouter")
     */
    public function ajouterChaton(ManagerRegistry $doctrine, Request $request)
    {
        $chaton = new Chaton();

        $form = $this->createForm(ChatonType::class, $chaton);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($chaton);
            $em->flush();

            //retour à l'accueil
            return $this->redirectToRoute("app_chatons_voir", ["idCategorie" => $chaton->getCategorie()->getId()]);
        }

        return $this->render("chatons/ajouter.html.twig", [
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/chaton/supprimer/{id}", name="app_chatons_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response{

        $chaton = $doctrine->getRepository(Chaton::class)->find($id);

        if (!$chaton){
            throw $this->createNotFoundException("Pas de catégorie avec l'id $id");
        }

        $form=$this->createForm(ChatonSupprimerType::class, $chaton);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em=$doctrine->getManager();
            $em->remove($chaton);

            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render("chatons/supprimer.html.twig",[
            "chaton"=>$chaton,
            "formulaire"=>$form->createView()
        ]);
    }
}
