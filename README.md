# Blog Multilingue Symfony

Un blog moderne et multilingue développé avec Symfony 7+, supportant le français, l'anglais et l'espagnol.

## 🚀 Fonctionnalités

### Frontend
- ✅ Page d'accueil avec liste paginée d'articles
- ✅ Sélecteur de langue visible et persistant
- ✅ Navigation multilingue avec URLs SEO-friendly
- ✅ Page de détails d'article traduit dans la langue active
- ✅ Système de recherche (titre et contenu)
- ✅ Interface responsive avec Bootstrap 5

### Articles
- ✅ Titre, résumé, contenu, slug traduisibles
- ✅ Date de publication
- ✅ Image à la une (upload via VichUploader)
- ✅ Catégories traduisibles
- ✅ SEO par langue (titre, description, image OG)
- ✅ Auteur et métadonnées

### Back-office (EasyAdmin)
- ✅ CRUD complet des articles avec interface de gestion des traductions
- ✅ CRUD catégories
- ✅ Authentification avec formulaire
- ✅ Rôles : admin / éditeur
- ✅ Upload d'images
- ✅ Interface d'administration moderne

### Multilingue
- ✅ Support complet : français (fr), anglais (en), espagnol (es)
- ✅ URLs localisées : `/fr/article/mon-slug`, `/en/article/my-slug`
- ✅ Traductions via le composant Symfony Translation
- ✅ Entités traduisibles avec système de traductions personnalisé

## 🛠️ Installation

### Prérequis
- Docker et Docker Compose
- Git

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone <repository-url>
   cd blog-multilingue-symfony
   ```

2. **Lancer l'environnement Docker**
   ```bash
   docker compose up -d
   ```

3. **Installer les dépendances**
   ```bash
   docker compose exec php composer install
   ```

4. **Créer la base de données et exécuter les migrations**
   ```bash
   docker compose exec php bin/console doctrine:database:create
   docker compose exec php bin/console doctrine:migrations:migrate --no-interaction
   ```

5. **Charger les fixtures (données de test)**
   ```bash
   docker compose exec php bin/console doctrine:fixtures:load --no-interaction
   ```

6. **Créer le dossier d'upload**
   ```bash
   docker compose exec php mkdir -p public/uploads/articles
   ```

## 🔐 Accès

### Frontend
- **URL** : http://localhost
- **Langues disponibles** : Français, Anglais, Espagnol

### Back-office
- **URL** : http://localhost/admin
- **Identifiants par défaut** :
  - **Admin** : `admin@blog.com` / `admin123`
  - **Éditeur** : `editor@blog.com` / `editor123`

## 📁 Structure du projet

```
blog-multilingue-symfony/
├── src/
│   ├── Controller/
│   │   ├── BlogController.php          # Contrôleur frontend
│   │   ├── AdminController.php         # Dashboard EasyAdmin
│   │   └── Admin/                      # CRUD EasyAdmin
│   │       ├── ArticleCrudController.php
│   │       ├── CategoryCrudController.php
│   │       └── UserCrudController.php
│   ├── Entity/
│   │   ├── Article.php                 # Entité Article
│   │   ├── ArticleTranslation.php      # Traductions Article
│   │   ├── Category.php                # Entité Category
│   │   ├── CategoryTranslation.php     # Traductions Category
│   │   └── User.php                    # Entité User
│   ├── Repository/
│   │   └── ArticleRepository.php       # Repository avec méthodes de recherche
│   └── DataFixtures/
│       └── AppFixtures.php             # Données de test multilingues
├── templates/
│   ├── base.html.twig                  # Layout principal
│   ├── blog/
│   │   ├── index.html.twig             # Page d'accueil
│   │   └── show.html.twig              # Page article
│   └── security/
│       └── login.html.twig             # Page de connexion
├── translations/
│   ├── messages.fr.yaml                # Traductions françaises
│   ├── messages.en.yaml                # Traductions anglaises
│   └── messages.es.yaml                # Traductions espagnoles
├── config/
│   ├── packages/
│   │   ├── doctrine.yaml               # Configuration Doctrine
│   │   ├── security.yaml               # Configuration sécurité
│   │   └── vich_uploader.yaml          # Configuration upload
│   └── routes.yaml                     # Routes
├── public/
│   └── uploads/
│       └── articles/                   # Images uploadées
├── docker-compose.yml                  # Configuration Docker
└── README.md                           # Ce fichier
```

## 🌐 Utilisation

### Navigation multilingue
- Les URLs sont automatiquement localisées : `/fr/`, `/en/`, `/es/`
- Le sélecteur de langue permet de changer de langue tout en conservant la page actuelle
- Les traductions sont automatiquement appliquées selon la langue sélectionnée

### Gestion des articles
1. Connectez-vous au back-office avec un compte admin
2. Accédez à "Articles" dans le menu
3. Créez un nouvel article en remplissant les champs pour chaque langue
4. Uploadez une image à la une si nécessaire
5. Publiez l'article

### Gestion des catégories
1. Dans le back-office, accédez à "Catégories"
2. Créez des catégories avec leurs traductions
3. Les catégories apparaîtront automatiquement dans la navigation

## 🔧 Configuration

### Base de données
Le projet utilise PostgreSQL par défaut. La configuration se trouve dans `docker-compose.yml` :
```yaml
DATABASE_URL: postgresql://app:!ChangeMe!@database:5432/app?serverVersion=15&charset=utf8
```

### Upload d'images
Les images sont configurées via VichUploader dans `config/packages/vich_uploader.yaml` :
```yaml
mappings:
    article_images:
        uri_prefix: /uploads/articles
        upload_destination: '%kernel.project_dir%/public/uploads/articles'
```

### Sécurité
- Authentification par formulaire
- Rôles : `ROLE_USER`, `ROLE_EDITOR`, `ROLE_ADMIN`
- Protection CSRF activée

## 🚀 Déploiement

### Production
1. Modifiez les variables d'environnement dans `.env.local`
2. Changez les mots de passe par défaut
3. Configurez votre serveur web pour pointer vers `public/`
4. Assurez-vous que le dossier `public/uploads/` est accessible en écriture

### Variables d'environnement importantes
```bash
DATABASE_URL=postgresql://user:password@host:5432/database
APP_SECRET=your-secret-key
```

## 🐛 Dépannage

### Problèmes courants

**Erreur de base de données**
```bash
docker compose exec php bin/console doctrine:database:create
docker compose exec php bin/console doctrine:migrations:migrate
```

**Problème d'upload d'images**
```bash
docker compose exec php mkdir -p public/uploads/articles
docker compose exec php chmod -R 777 public/uploads
```

**Cache Symfony**
```bash
docker compose exec php bin/console cache:clear
```

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## 📞 Support

Pour toute question ou problème, n'hésitez pas à ouvrir une issue sur GitHub.
