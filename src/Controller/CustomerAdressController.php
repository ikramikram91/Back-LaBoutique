<?php

namespace App\Controller;

use App\Entity\CustomerAdress;
use App\Form\CustomerAdressType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerAdressController extends AbstractController
{
    #[Route('/customer/adress', name: 'customer_adress')]
    public function index( Request $request): Response
    {

        $adress = new CustomerAdress();
        $form = $this->createForm(CustomerAdressType::class, $adress);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($adress);
            $em->flush();

            return $this->redirectToRoute('checkout');
        }

        return $this->render('customer_adress/index.html.twig', [
            'controller_name' => 'CustomerAdressController',
            'form'=>$form->createView()
        ]);
    }


}
