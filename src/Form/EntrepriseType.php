<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_entreprise', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'attr' => ['class' => 'form-control']
            ])
            ->add('secteur_activite', TextType::class, [
                'label' => 'Secteur d\'activité',
                'attr' => ['class' => 'form-control']
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control']
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'attr' => ['class' => 'form-control']
            ])
            ->add('code_postal', TextType::class, [
                'label' => 'Code postal',
                'attr' => ['class' => 'form-control']
            ])
            ->add('pays', TextType::class, [
                'label' => 'Pays',
                'attr' => ['class' => 'form-control']
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone',
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 5]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
} 