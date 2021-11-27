<?php

namespace App\Form;

use App\Entity\Comment;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => 'votre e-mail'
            ])
            ->add('name', TextType::class,[
                'label' => 'name'
            ])
            ->add('content', TextareaType::class,[
                'label' => 'votre commentaire'
            ])
            ->add('parent', HiddenType::class,[
                'mapped' => false
            ] ) 
            ->add('rgpd', CheckboxType::class,[
                'constraints' => [
                    new NotBlank() // permet la protection cotés serveur pour ne pas bidouiller le code en inspecteur obliger a cocher la case même si on touche au code on retirant le require
                ],
                'label' => 'En cochant cette case vous acceptez que vos données soient conservées'
            ] )
            ->add('submit', SubmitType::class,[
                'label' => 'Envoyer'
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
