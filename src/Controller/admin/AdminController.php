<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Entity\Product;
use App\Form\AjoutProductType;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

// ajouter un produit 
    #[Route('/product/ajout', name: 'product_ajout')]
    public function productAjout(Request $request)
    {
        $produit = new Product;
        
        $form = $this->createForm(AjoutProductType::class, $produit);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }


//ajouter une categorie
    #[Route('/ajout/categorie', name: 'ajout_categorie')]
    public function AjoutCategorie(Request $request)
    {
        $categorie = new Categorie;
        
        $formAjoutCategorie = $this->createForm(CategorieType::class, $categorie);
        
        $formAjoutCategorie->handleRequest($request);

        if($formAjoutCategorie->isSubmitted() && $formAjoutCategorie->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('admin_product_ajout');
        }
        return $this->render('admin/ajoutCategorie.html.twig', [
            'formAjoutCategorie' => $formAjoutCategorie->createView()
        ]);
    }
}