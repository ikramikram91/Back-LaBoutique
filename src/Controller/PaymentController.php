<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }


    #[Route('/checkout', name: 'checkout')]
    public function checkout($stripeSK,SessionInterface $session, ProductRepository $productsRepository): Response
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;
        $ajout = 100;
        

        foreach($panier as $id => $quantite){
            $product = $productsRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite
            ];
            $total   +=$product->getPrice() * $quantite ;
        }

        Stripe::setApiKey($stripeSK);

        $session = Session::create([
            'payment_method_types'=>['card'],
                'line_items'=>[[

                    'price_data'=>[
                        'currency'=>'eur',
                        'product_data' =>[
                            'name'=> 'totale a payer',
                        
                    ],
                        'unit_amount_decimal'=> $total*$ajout,
                        // dd($total)
                    ],
                    'quantity'=> 1,
                ]],
                'mode' =>'payment',
                'success_url'=> $this->generateUrl('success_url', [],
                UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('cancel_url',[],
            UrlGeneratorInterface::ABSOLUTE_URL),
                ]);

                  
                  return $this->redirect($session->url,303)
        
        ;
    }

    
    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('payment/success.html.twig', []);
    }


    
    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig', []);
    }
}
