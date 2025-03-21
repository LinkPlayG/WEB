<?php

namespace App\Form;

use App\Entity\OffreDeStage;
use App\Entity\Entreprise;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreDeStageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du stage',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 5]
            ])
            ->add('competences_requises', TextareaType::class, [
                'label' => 'Compétences requises',
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ])
            ->add('duree', NumberType::class, [
                'label' => 'Durée (en semaines)',
                'attr' => ['class' => 'form-control']
            ])
            ->add('date_debut', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('remuneration', NumberType::class, [
                'label' => 'Rémunération mensuelle (€)',
                'attr' => ['class' => 'form-control']
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom_entreprise',
                'label' => 'Entreprise',
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffreDeStage::class,
        ]);
    }
} 