<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Article')
            ->setEntityLabelInPlural('Articles')
            ->setSearchFields(['translations.title', 'translations.content', 'slug'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPageTitle('index', 'Gestion des articles')
            ->setPageTitle('new', 'Créer un article')
            ->setPageTitle('edit', 'Modifier l\'article')
            ->setPageTitle('detail', 'Détails de l\'article');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('slug', 'Slug')
            ->setHelp('URL de l\'article (généré automatiquement)');

        yield AssociationField::new('category', 'Catégorie')
            ->setFormTypeOption('query_builder', function ($repository) {
                return $repository->createQueryBuilder('c')
                    ->where('c.isActive = :active')
                    ->setParameter('active', true)
                    ->orderBy('c.name', 'ASC');
            });

        yield AssociationField::new('author', 'Auteur');

        yield BooleanField::new('isPublished', 'Publié');

        yield DateTimeField::new('publishedAt', 'Date de publication')
            ->setHelp('Date à laquelle l\'article sera publié');

        yield ImageField::new('imageName', 'Image à la une')
            ->setBasePath('uploads/articles')
            ->setUploadDir('public/uploads/articles')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false);

        // Champs de traduction
        yield TextField::new('title', 'Titre (FR)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Titre en français');

        yield TextareaField::new('summary', 'Résumé (FR)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Résumé en français');

        yield TextEditorField::new('content', 'Contenu (FR)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Contenu en français');

        yield TextField::new('titleEn', 'Titre (EN)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Titre en anglais');

        yield TextareaField::new('summaryEn', 'Résumé (EN)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Résumé en anglais');

        yield TextEditorField::new('contentEn', 'Contenu (EN)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Contenu en anglais');

        yield TextField::new('titleEs', 'Titre (ES)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Titre en espagnol');

        yield TextareaField::new('summaryEs', 'Résumé (ES)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Résumé en espagnol');

        yield TextEditorField::new('contentEs', 'Contenu (ES)')
            ->setFormTypeOption('mapped', false)
            ->setHelp('Contenu en espagnol');

        // SEO
        yield TextField::new('seoTitle', 'Titre SEO')
            ->setHelp('Titre pour les moteurs de recherche');

        yield TextareaField::new('seoDescription', 'Description SEO')
            ->setHelp('Description pour les moteurs de recherche');

        yield TextField::new('seoImage', 'Image SEO')
            ->setHelp('Image pour les réseaux sociaux');

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
                return $action->setLabel('Créer un article');
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

    private function handleTranslations(Article $article): void
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $formData = $request->request->get('Article');

        // Français
        if (isset($formData['title'])) {
            $article->setTitle($formData['title'], 'fr');
        }
        if (isset($formData['summary'])) {
            $article->setSummary($formData['summary'], 'fr');
        }
        if (isset($formData['content'])) {
            $article->setContent($formData['content'], 'fr');
        }

        // Anglais
        if (isset($formData['titleEn'])) {
            $article->setTitle($formData['titleEn'], 'en');
        }
        if (isset($formData['summaryEn'])) {
            $article->setSummary($formData['summaryEn'], 'en');
        }
        if (isset($formData['contentEn'])) {
            $article->setContent($formData['contentEn'], 'en');
        }

        // Espagnol
        if (isset($formData['titleEs'])) {
            $article->setTitle($formData['titleEs'], 'es');
        }
        if (isset($formData['summaryEs'])) {
            $article->setSummary($formData['summaryEs'], 'es');
        }
        if (isset($formData['contentEs'])) {
            $article->setContent($formData['contentEs'], 'es');
        }
    }
} 