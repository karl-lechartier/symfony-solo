<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Proprietaire;
use App\Form\CategorieSupprimerType;
use App\Form\CategorieType;
use App\Form\ProprietaireSupprimerType;
use App\Form\ProprietaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProprietaireController extends AbstractController
{

    /**
     * @Route("/proprietaire/voir", name="app_proprietaires_voir")
     */
    public function index(ManagerRegistry $doctrine): Response
    {

        //On va aller chercher les catégories dans la BDD
        //pour ça on a besoin d'un repository
        $repo = $doctrine->getRepository(Proprietaire::class);
        $proprietaire=$repo->findAll(); //select * transformé en liste de Categorie

        return $this->render('proprietaire/index.html.twig', [
            'proprietaire'=>$proprietaire
        ]);
    }

    /**
     * @Route("/proprietaire/ajouter", name="app_proprietaires_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        $proprietaire=new Proprietaire();
        $form=$this->createForm(ProprietaireType::class, $proprietaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em=$doctrine->getManager();
            $em->persist($proprietaire);

            $em->flush();

            return $this->redirectToRoute("app_proprietaires_voir");
        }

        return $this->render("proprietaire/ajouter.html.twig",[
           "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/proprietaire/modifier/{id}", name="app_proprietaires_modifier")
     */
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response{
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);

        if (!$proprietaire){
            throw $this->createNotFoundException("Pas de catégorie avec l'id $id");
        }

        $form=$this->createForm(ProprietaireType::class, $proprietaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em=$doctrine->getManager();
            $em->persist($proprietaire);

            $em->flush();

            return $this->redirectToRoute("app_proprietaires_voir");
        }

        return $this->render("proprietaire/modifier.html.twig",[
            "proprietaire"=>$proprietaire,
            "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/proprietaire/supprimer/{id}", name="app_proprietaires_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response{
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);

        if (!$proprietaire){
            throw $this->createNotFoundException("Pas de catégorie avec l'id $id");
        }

        $form=$this->createForm(ProprietaireSupprimerType::class, $proprietaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->remove($proprietaire);

            $em->flush();
            return $this->redirectToRoute("app_proprietaires_voir");
        }

        return $this->render("proprietaire/supprimer.html.twig",[
            "proprietaire"=>$proprietaire,
            "formulaire"=>$form->createView()
        ]);
    }
}
