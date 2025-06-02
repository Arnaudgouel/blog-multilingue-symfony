<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Créer les utilisateurs
        $admin = new User();
        $admin->setEmail('admin@blog.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        $editor = new User();
        $editor->setEmail('editor@blog.com');
        $editor->setRoles(['ROLE_EDITOR']);
        $editor->setPassword($this->passwordHasher->hashPassword($editor, 'editor123'));
        $manager->persist($editor);

        // Créer les catégories
        $categories = [
            'technologie' => [
                'fr' => ['name' => 'Technologie', 'description' => 'Articles sur les nouvelles technologies'],
                'en' => ['name' => 'Technology', 'description' => 'Articles about new technologies'],
                'es' => ['name' => 'Tecnología', 'description' => 'Artículos sobre nuevas tecnologías']
            ],
            'voyage' => [
                'fr' => ['name' => 'Voyage', 'description' => 'Découvrez le monde avec nos articles de voyage'],
                'en' => ['name' => 'Travel', 'description' => 'Discover the world with our travel articles'],
                'es' => ['name' => 'Viajes', 'description' => 'Descubre el mundo con nuestros artículos de viajes']
            ],
            'cuisine' => [
                'fr' => ['name' => 'Cuisine', 'description' => 'Recettes et conseils culinaires'],
                'en' => ['name' => 'Cooking', 'description' => 'Recipes and culinary tips'],
                'es' => ['name' => 'Cocina', 'description' => 'Recetas y consejos culinarios']
            ]
        ];

        foreach ($categories as $slug => $translations) {
            $category = new Category();
            $category->setSlug($slug);
            $category->setIsActive(true);
            
            foreach ($translations as $locale => $data) {
                $category->setName($data['name'], $locale);
                $category->setDescription($data['description'], $locale);
            }
            
            $manager->persist($category);
        }

        // Créer les articles
        $articles = [
            [
                'slug' => 'intelligence-artificielle-2024',
                'category' => 'technologie',
                'author' => $admin,
                'translations' => [
                    'fr' => [
                        'title' => 'L\'Intelligence Artificielle en 2024',
                        'summary' => 'Découvrez les dernières avancées en intelligence artificielle et leur impact sur notre société.',
                        'content' => '<p>L\'intelligence artificielle continue d\'évoluer à un rythme rapide en 2024. Les nouvelles technologies comme GPT-4, les modèles multimodaux et l\'IA générative transforment de nombreux secteurs.</p><p>Dans cet article, nous explorons les tendances actuelles et les défis à venir pour l\'IA.</p>'
                    ],
                    'en' => [
                        'title' => 'Artificial Intelligence in 2024',
                        'summary' => 'Discover the latest advances in artificial intelligence and their impact on our society.',
                        'content' => '<p>Artificial intelligence continues to evolve at a rapid pace in 2024. New technologies like GPT-4, multimodal models, and generative AI are transforming many sectors.</p><p>In this article, we explore current trends and upcoming challenges for AI.</p>'
                    ],
                    'es' => [
                        'title' => 'La Inteligencia Artificial en 2024',
                        'summary' => 'Descubre los últimos avances en inteligencia artificial y su impacto en nuestra sociedad.',
                        'content' => '<p>La inteligencia artificial continúa evolucionando a un ritmo rápido en 2024. Las nuevas tecnologías como GPT-4, los modelos multimodales y la IA generativa están transformando muchos sectores.</p><p>En este artículo, exploramos las tendencias actuales y los desafíos venideros para la IA.</p>'
                    ]
                ]
            ],
            [
                'slug' => 'paris-ville-lumiere',
                'category' => 'voyage',
                'author' => $editor,
                'translations' => [
                    'fr' => [
                        'title' => 'Paris, la Ville Lumière',
                        'summary' => 'Un guide complet pour découvrir Paris, ses monuments et sa culture unique.',
                        'content' => '<p>Paris, capitale de la France, est l\'une des villes les plus visitées au monde. Avec ses monuments emblématiques comme la Tour Eiffel, le Louvre et Notre-Dame, Paris offre une expérience culturelle incomparable.</p><p>Découvrez nos conseils pour visiter la ville de manière optimale.</p>'
                    ],
                    'en' => [
                        'title' => 'Paris, the City of Light',
                        'summary' => 'A complete guide to discover Paris, its monuments and unique culture.',
                        'content' => '<p>Paris, the capital of France, is one of the most visited cities in the world. With its iconic monuments like the Eiffel Tower, the Louvre and Notre-Dame, Paris offers an incomparable cultural experience.</p><p>Discover our tips for visiting the city optimally.</p>'
                    ],
                    'es' => [
                        'title' => 'París, la Ciudad de la Luz',
                        'summary' => 'Una guía completa para descubrir París, sus monumentos y su cultura única.',
                        'content' => '<p>París, la capital de Francia, es una de las ciudades más visitadas del mundo. Con sus monumentos icónicos como la Torre Eiffel, el Louvre y Notre-Dame, París ofrece una experiencia cultural incomparable.</p><p>Descubre nuestros consejos para visitar la ciudad de manera óptima.</p>'
                    ]
                ]
            ],
            [
                'slug' => 'cuisine-francaise-traditionnelle',
                'category' => 'cuisine',
                'author' => $editor,
                'translations' => [
                    'fr' => [
                        'title' => 'La Cuisine Française Traditionnelle',
                        'summary' => 'Explorez les secrets de la gastronomie française et ses recettes emblématiques.',
                        'content' => '<p>La cuisine française est reconnue dans le monde entier pour sa sophistication et sa diversité. Du coq au vin aux crêpes bretonnes, découvrez les recettes qui ont fait la réputation de la gastronomie française.</p><p>Nous vous proposons des recettes authentiques et des conseils pour réussir vos plats.</p>'
                    ],
                    'en' => [
                        'title' => 'Traditional French Cuisine',
                        'summary' => 'Explore the secrets of French gastronomy and its iconic recipes.',
                        'content' => '<p>French cuisine is recognized worldwide for its sophistication and diversity. From coq au vin to Breton crepes, discover the recipes that have made French gastronomy famous.</p><p>We offer you authentic recipes and tips to succeed in your dishes.</p>'
                    ],
                    'es' => [
                        'title' => 'La Cocina Francesa Tradicional',
                        'summary' => 'Explora los secretos de la gastronomía francesa y sus recetas icónicas.',
                        'content' => '<p>La cocina francesa es reconocida en todo el mundo por su sofisticación y diversidad. Desde el coq au vin hasta las crêpes bretonas, descubre las recetas que han hecho famosa la gastronomía francesa.</p><p>Te ofrecemos recetas auténticas y consejos para triunfar en tus platos.</p>'
                    ]
                ]
            ]
        ];

        foreach ($articles as $articleData) {
            $article = new Article();
            $article->setSlug($articleData['slug']);
            $article->setIsPublished(true);
            $article->setPublishedAt(new \DateTimeImmutable());
            $article->setCategory($manager->getRepository(Category::class)->findOneBy(['slug' => $articleData['category']]));
            $article->setAuthor($articleData['author']);
            
            foreach ($articleData['translations'] as $locale => $data) {
                $article->setTitle($data['title'], $locale);
                $article->setSummary($data['summary'], $locale);
                $article->setContent($data['content'], $locale);
            }
            
            $manager->persist($article);
        }

        $manager->flush();
    }
}
