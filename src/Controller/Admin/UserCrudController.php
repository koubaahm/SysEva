<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;

#[IsGranted('ROLE_ADMIN')]
class UserCrudController extends AbstractCrudController implements EventSubscriberInterface
{



    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {

    }


    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ->add(Crud::PAGE_NEW, Action::INDEX);
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('nom')->setLabel('First Name'),
            TextField::new('prenom')->setLabel('Last Name'),
            TextField::new('numtel')->setLabel('Phone Number'),
            TextField::new('laboratoire')->setLabel('University'),
            BooleanField::new('etat')
                ->renderAsSwitch(false)
                ->setLabel('Account activated ?'),
            EmailField::new('email')->setLabel('E-mail'),
            ChoiceField::new('roles')
                ->setChoices([
                    'Administrateur' => 'ROLE_ADMIN',
                    'Editeur en chef' => 'ROLE_CHIEF_EDITOR',
                    'Editeur senior' => 'ROLE_SENIOR_EDITOR',
                    'Editeur de section' => 'ROLE_SECTION_EDITOR',
                    'Reviewer' => 'ROLE_REVIEWER'
                ])
                ->allowMultipleChoices()
                ->setFormType(ChoiceType::class),
        ];

        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            $fields[] = TextField::new('password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => '(Repeat)'],
                    'mapped' => false,
                ])
                ->setRequired($pageName === Crud::PAGE_NEW)
                ->onlyOnForms();
        }

        return $fields;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        return $this->addPasswordEventListener(parent::createNewFormBuilder($entityDto, $formOptions, $context));
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        return $this->addPasswordEventListener(parent::createEditFormBuilder($entityDto, $formOptions, $context));
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }

            $data = $form->getData();
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            $hash = $this->userPasswordHasher->hashPassword($data, $password);
            $data->setPassword($hash);
        });
    }

    public function onBeforeCrudAction(BeforeCrudActionEvent $event)
    {
        dump('L\'événement BeforeCrudActionEvent a été déclenché.');
    }

    public static function getSubscribedEvents(): array
    {
        return [
                // BeforeEntityPersistedEvent::class => 'onBeforeEntityPersisted',
            BeforeCrudActionEvent::class => 'onBeforeCrudAction', // Ajouter cet événement
        ];
    }




}

