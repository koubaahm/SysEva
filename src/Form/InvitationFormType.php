<?php
namespace App\Form;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Keyword;
use App\Form\Model\InvitationModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormInterface;

class InvitationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('section', EntityType::class, [
                'class' => Keyword::class,
                'choice_label' => 'name',
                'placeholder' => 'Select All',
                'mapped' => false,
                'attr' => ['class' => 'select2'],
                'label' => 'Select Keyword',
                'required' => false
            ])->add('users', EntityType::class, [
                    'class' => User::class,
                    'choice_label' => function (User $user) {
                        return sprintf('%s (%s)', $user->getNom(), $user->getEmail());
                    },
                    'multiple' => true,
                    'expanded' => false,
                    'attr' => ['class' => 'select2'],
                    'label' => 'Select Users',
                ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $this->addArticlesField($form, null);
            $this->addUsersField($form, null);
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $articleId = $data['article'] ?? null;
            $userId = $data['user'] ?? null;
            $this->addArticlesField($form, $articleId);
            $this->addUsersField($form, $userId);
        });
    }

    private function addArticlesField(FormInterface $form, ?int $sectionId)
    {
        $form->add('articles', EntityType::class, [
            'class' => Article::class,
            'choice_label' => 'titre',
            'multiple' => true,
            'expanded' => false,
            'attr' => ['class' => 'select2'],
            'label' => 'Select Articles',
            'query_builder' => function (EntityRepository $er) use ($sectionId) {
                $qb = $er->createQueryBuilder('a');
                if ($sectionId && $sectionId !== 'all') {
                    $qb->where('a.section = :section')
                        ->setParameter('section', $sectionId);
                }
                $qb->orderBy('a.titre', 'ASC');
                return $qb;
            },
        ]);
    }

    private function addUsersField(FormInterface $form, ?int $sectionId)
    {
        $form->add('users', EntityType::class, [
            'class' => User::class,
            'choice_label' => function (User $user) {
                return sprintf('%s (%s)', $user->getNom(), $user->getEmail());
            },
            'multiple' => true,
            'expanded' => false,
            'attr' => ['class' => 'select2'],
            'label' => 'Select Users',
            'query_builder' => function (EntityRepository $er) use ($sectionId) {
                $qb = $er->createQueryBuilder('u');
                if ($sectionId && $sectionId !== 'all') {
                    $qb->join('u.MySection', 's')
                        ->where('s.id = :sectionId')
                        ->setParameter('sectionId', $sectionId);
                }
                $qb->orderBy('u.nom', 'ASC');
                return $qb;
            },
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvitationModel::class,
        ]);
    }
}