<?php

namespace App\Controller\admin;

use App\Entity\Product;
use App\Form\Product1Type;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class ProductCrudController extends AbstractController
{
    #[Route('/', name: 'product_crud_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product_crud/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'product_crud_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(Product1Type::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on récup les image transmise 
            $images = $form->get('images')->getData();

            // on boucle sur les images
            foreach($images as $image){
                // génére un nouveau nom de fichier 
            $fichier = md5(uniqid()). '.' . $image->guessExtension();

            // on copie le fichier dans le dossier image
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            ); 
             $product->setPicture($fichier);
        }
            // on stoke le nom de l'image dans la base de donner dans l'entity product column picture 
          
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_crud/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'product_crud_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product_crud/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'product_crud_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(Product1Type::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on récup les image transmise 
            $images = $form->get('images')->getData();

            // on boucle sur les images
            foreach($images as $image){
                // génére un nouveau nom de fichier 
            $fichier = md5(uniqid()). '.' . $image->guessExtension();

            // on copie le fichier dans le dossier image
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            ); 
            $product->setPicture($fichier);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_crud/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'product_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}