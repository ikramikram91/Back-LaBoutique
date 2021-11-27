<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentaireType;
use App\Form\FormContactType;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use Container7S7ufBg\getMimeTypesService;
use DateTimeImmutable;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
   
    
    #[Route('/product', name: 'product')]
    public function product(ProductRepository $productRepository, Request $request, MailerInterface $mailer)
    {
    
        return $this->render('product/index.html.twig', [
            'product' => $productRepository->findByCategorie('bracelet'),
            
            
        ]);
      
    }

    #[Route('/product/cheville', name: 'product_cheville')]
    public function productCheville(ProductRepository $productRepository, Request $request, MailerInterface $mailer)
    {
    
        return $this->render('product/cheville.html.twig', [
            'product' => $productRepository->findByCategorie('cheville'),
            
            
        ]);
      
    }

    #[Route('/product/hanche', name: 'product_hanche')]
    public function productHanche(ProductRepository $productRepository, Request $request, MailerInterface $mailer)
    {
    
        return $this->render('product/hanche.html.twig', [
            'product' => $productRepository->findByCategorie('hanche'),
            
            
        ]);
      
    }
    #[Route('/product/form', name: 'product_index')] 
    public function index( Request $request, MailerInterface $mailer)
    {
        // formulaire d'envoie d'email de contact
        $formContact = $this->createForm( FormContactType::class);

        $contact = $formContact->handleRequest($request);
        if($formContact->isSubmitted() && $formContact->isValid()){
            $email = (new TemplatedEmail()) 
                ->from($contact->get('email')->getData())//récup email de la personne qui envoie le message 
                ->to(new Address('ikramdjellouli@laboutiquedelaperle.fr'))
                ->htmlTemplate('email/contact.html.twig')
                ->context([
                    'firstname' => $contact->get('firstname')->getData(),//récup les information du formulaire 
                    'name' => $contact->get('name')->getData(),
                    'mail' => $contact->get('email')->getData(),
                    'subject' => $contact->get('subject')->getData(),
                    'message' => $contact->get('message')->getData()
                ]);

            //envoie le mail
            $mailer->send($email);


            //confirm et redirige
            $this->addFlash('message', 'message envoyer');
            return $this->redirectToRoute('product');
        }




        return $this->render("contact/index.html.twig", [
            'contact' => $contact->createView(),
        ]);
      
    }
//affiche le details d'un produit avec ses commentaire 
    #[Route('/product/details/{id}', name: 'product_detail' )]
    public function details($id,ProductRepository $productRepository, CommentRepository $commentRepository, Request $request)
    {
// on récupère l'annonce qui nous interesse 

    $product = $productRepository->find($id);


//partie commentaire 
//crée le commentaire vierge 
    $comment = new Comment;
// on récupère le formulaire 
    $form = $this->createForm(CommentaireType::class, $comment);
    $form->handleRequest($request);

//traitement du formulaire 

    if($form->isSubmitted() && $form->isValid()){
       $comment->setCreatedAt(new DateTimeImmutable());
       $comment->setProduct($product);

// je récup le contenue du champ parentid
       $parentid = $form->get("parent")->getData();

//je récup le commentaire correspondant
       $em = $this->getDoctrine()->getManager();
// vérifie dans le base si il existe un comment avec la valeur parentid
    if($parentid != null){
           $parent = $em->getRepository(Comment::class)->find($parentid);
    }


//je def le parent 
       $comment->setParent($parent ?? null );

       
       $em->persist($comment);
       $em->flush();

       $this->addFlash('message', 'votre commentaire a bien été envoyer!');
       return $this->redirectToRoute('product_detail',['id' => $product->getId()]);
    }

    




return $this->render('product/details.html.twig', [
        'product' => $product,
        'form'    => $form->createView()
    ]);



}


    
}