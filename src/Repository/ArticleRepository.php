<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Article[] Returns an array of published articles
     */
    public function findPublishedArticles(string $locale = 'fr', int $limit = 10, int $offset = 0): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.isPublished = :published')
            ->andWhere('a.publishedAt <= :now')
            ->setParameter('published', true)
            ->setParameter('now', new \DateTimeImmutable())
            ->orderBy('a.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Article[] Returns an array of published articles by category
     */
    public function findPublishedArticlesByCategory(int $categoryId, string $locale = 'fr', int $limit = 10, int $offset = 0): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.isPublished = :published')
            ->andWhere('a.publishedAt <= :now')
            ->andWhere('a.category = :categoryId')
            ->setParameter('published', true)
            ->setParameter('now', new \DateTimeImmutable())
            ->setParameter('categoryId', $categoryId)
            ->orderBy('a.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find article by slug and locale
     */
    public function findOneBySlugAndLocale(string $slug, string $locale = 'fr'): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.slug = :slug')
            ->andWhere('a.isPublished = :published')
            ->andWhere('a.publishedAt <= :now')
            ->setParameter('slug', $slug)
            ->setParameter('published', true)
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Search articles by title and content
     */
    public function searchArticles(string $query, string $locale = 'fr', int $limit = 10): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.translations', 't')
            ->andWhere('a.isPublished = :published')
            ->andWhere('a.publishedAt <= :now')
            ->andWhere('t.locale = :locale')
            ->andWhere('(t.title LIKE :query OR t.content LIKE :query OR t.summary LIKE :query)')
            ->setParameter('published', true)
            ->setParameter('now', new \DateTimeImmutable())
            ->setParameter('locale', $locale)
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('a.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Count published articles
     */
    public function countPublishedArticles(): int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->andWhere('a.isPublished = :published')
            ->andWhere('a.publishedAt <= :now')
            ->setParameter('published', true)
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->getSingleScalarResult();
    }
}
