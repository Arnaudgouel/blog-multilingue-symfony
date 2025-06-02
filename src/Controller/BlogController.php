<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}', requirements: ['_locale' => 'fr|en|es'], defaults: ['_locale' => 'fr'])]
class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog_index', methods: ['GET'])]
    public function index(
        Request $request,
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $articles = $articleRepository->findPublishedArticles($request->getLocale(), $limit, $offset);
        $totalArticles = $articleRepository->countPublishedArticles();
        $totalPages = ceil($totalArticles / $limit);
        
        $categories = $categoryRepository->findBy(['isActive' => true]);

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'locale' => $request->getLocale(),
        ]);
    }

    #[Route('/article/{slug}', name: 'app_blog_article_show', methods: ['GET'])]
    public function show(Article $article, Request $request): Response
    {
        if (!$article->isPublished() || $article->getPublishedAt() > new \DateTimeImmutable()) {
            throw $this->createNotFoundException('Article not found');
        }

        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'locale' => $request->getLocale(),
        ]);
    }

    #[Route('/category/{slug}', name: 'app_blog_category_show', methods: ['GET'])]
    public function category(
        Category $category,
        Request $request,
        ArticleRepository $articleRepository
    ): Response {
        if (!$category->isActive()) {
            throw $this->createNotFoundException('Category not found');
        }

        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $articles = $articleRepository->findPublishedArticlesByCategory(
            $category->getId(),
            $request->getLocale(),
            $limit,
            $offset
        );

        return $this->render('blog/category.html.twig', [
            'category' => $category,
            'articles' => $articles,
            'currentPage' => $page,
            'locale' => $request->getLocale(),
        ]);
    }

    #[Route('/search', name: 'app_blog_search', methods: ['GET'])]
    public function search(
        Request $request,
        ArticleRepository $articleRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $query = $request->query->get('q', '');
        
        if (empty($query)) {
            return $this->redirectToRoute('app_blog_index');
        }

        $articles = $articleRepository->searchArticles($query, $request->getLocale());
        $categories = $categoryRepository->findBy(['isActive' => true]);

        return $this->render('blog/search.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'query' => $query,
            'locale' => $request->getLocale(),
        ]);
    }
} 