<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->setSearchFields(['email'])
            ->setDefaultSort(['email' => 'ASC'])
            ->setPageTitle('index', 'Gestion des utilisateurs')
            ->setPageTitle('new', 'Créer un utilisateur')
            ->setPageTitle('edit', 'Modifier l\'utilisateur')
            ->setPageTitle('detail', 'Détails de l\'utilisateur');
    }

    public function configureFields(string $pageName): iterable
    {
        yield EmailField::new('email', 'Email');

        yield TextField::new('password', 'Mot de passe')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Laissez vide pour ne pas modifier le mot de passe')
            ->hideOnIndex();

        yield ArrayField::new('roles', 'Rôles')
            ->setFormType(ChoiceType::class)
            ->setFormTypeOptions([
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Éditeur' => 'ROLE_EDITOR',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Créer un utilisateur');
            });
    }

    public function persistEntity($entityManager, $entityInstance): void
    {
        $this->hashPassword($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity($entityManager, $entityInstance): void
    {
        $this->hashPassword($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function hashPassword(User $user): void
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $formData = $request->request->get('User');

        if (isset($formData['password']) && !empty($formData['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $formData['password']);
            $user->setPassword($hashedPassword);
        }
    }
} 