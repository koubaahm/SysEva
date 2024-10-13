<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Keyword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;

class UserType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $keywords = $this->entityManager->getRepository(Keyword::class)->findAll();
        $choices = [];

        foreach ($keywords as $keyword) {
            $section = $keyword->getSection();
            $acronyme = $section ? $section->getAcronyme() : '';
            $choices[$keyword->getName() . ' (' . $acronyme . ')'] = $keyword->getName();
        }

        $builder
            ->add('nom', TextType::class, [
                'label' => 'Last Name',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'First Name',
            ])
            ->add('numTel', TextType::class, [
                'label' => 'Phone Number',
            ])
            ->add('laboratoire', TextType::class, [
                'label' => 'Lab',
            ])
            ->add('competences', ChoiceType::class, [
                'choices' => $choices,
                'multiple' => true,
                'expanded' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}