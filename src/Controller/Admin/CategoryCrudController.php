<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories')
            ->setSearchFields(['translations.name', 'translations.description', 'slug'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPageTitle('index', 'Gestion des catégories')
            ->setPageTitle('new', 'Créer une catégorie')
            ->setPageTitle('edit', 'Modifier la catégorie')
            ->setPageTitle('detail', 'Détails de la catégorie');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('slug', 'Slug')
            ->setHelp('URL de la catégorie (généré automatiquement)');

        yield BooleanField::new('isActive', 'Active');

        // Champs de traduction
        yield TextField::new('name', 'Nom (FR)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Nom en français');

        yield TextareaField::new('description', 'Description (FR)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Description en français');

        yield TextField::new('nameEn', 'Nom (EN)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Nom en anglais');

        yield TextareaField::new('descriptionEn', 'Description (EN)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Description en anglais');

        yield TextField::new('nameEs', 'Nom (ES)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Nom en espagnol');

        yield TextareaField::new('descriptionEs', 'Description (ES)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Description en espagnol');

        yield DateTimeField::new('createdAt', 'Créé le')
            ->hideOnForm();

        yield DateTimeField::new('updatedAt', 'Modifié le')
            ->hideOnForm();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Créer une catégorie');
            });
    }

    public function persistEntity($entityManager, $entityInstance): void
    {
        // Gérer les traductions
        $this->handleTranslations($entityInstance);
        
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity($entityManager, $entityInstance): void
    {
        // Gérer les traductions
        $this->handleTranslations($entityInstance);
        
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function handleTranslations(Category $category): void
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $formData = $request->request->get('Category');

        // Français
        if (isset($formData['name'])) {
            $category->setName($formData['name'], 'fr');
        }
        if (isset($formData['description'])) {
            $category->setDescription($formData['description'], 'fr');
        }

        // Anglais
        if (isset($formData['nameEn'])) {
            $category->setName($formData['nameEn'], 'en');
        }
        if (isset($formData['descriptionEn'])) {
            $category->setDescription($formData['descriptionEn'], 'en');
        }

        // Espagnol
        if (isset($formData['nameEs'])) {
            $category->setName($formData['nameEs'], 'es');
        }
        if (isset($formData['descriptionEs'])) {
            $category->setDescription($formData['descriptionEs'], 'es');
        }
    }
} 