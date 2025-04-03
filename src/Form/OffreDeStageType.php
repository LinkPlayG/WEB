<?php

namespace App\Form;

use App\Entity\OffreDeStage;
use App\Entity\Entreprise;
use App\Entity\PiloteDePromotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;

class OffreDeStageType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre_offre', TextType::class, [
                'label' => 'Titre du stage',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description_offre', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 5]
            ])
            ->add('competences_requises', TextareaType::class, [
                'label' => 'Compétences requises',
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ])
            ->add('duree_stage', NumberType::class, [
                'label' => 'Durée (en semaines)',
                'attr' => ['class' => 'form-control']
            ])
            ->add('date_debut_stage', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('date_fin_stage', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('salaire', NumberType::class, [
                'label' => 'Rémunération mensuelle (€)',
                'attr' => ['class' => 'form-control']
            ])
            ->add('statut_offre', ChoiceType::class, [
                'label' => 'Statut de l\'offre',
                'choices' => [
                    'Disponible' => 'Disponible',
                    'Pourvue' => 'Pourvue',
                    'Expirée' => 'Expirée'
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nom',
                'label' => 'Entreprise',
                'attr' => ['class' => 'form-control']
            ])
        ;

        // Si l'utilisateur est un admin, on ajoute le champ pour sélectionner le pilote
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('pilote', EntityType::class, [
                'class' => PiloteDePromotion::class,
                'choice_label' => function(PiloteDePromotion $pilote) {
                    return $pilote->getNomPilote() . ' ' . $pilote->getPrenomPilote();
                },
                'label' => 'Pilote responsable',
                'attr' => ['class' => 'form-control']
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffreDeStage::class,
        ]);
    }
} 