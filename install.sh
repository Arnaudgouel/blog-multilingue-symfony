#!/bin/bash

echo "🚀 Installation du Blog Multilingue Symfony"
echo "=============================================="

# Vérifier que Docker est installé
if ! command -v docker &> /dev/null; then
    echo "❌ Docker n'est pas installé. Veuillez installer Docker d'abord."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose n'est pas installé. Veuillez installer Docker Compose d'abord."
    exit 1
fi

echo "✅ Docker et Docker Compose sont installés"

# Lancer l'environnement Docker
echo "🐳 Lancement de l'environnement Docker..."
docker compose up -d

# Attendre que les services soient prêts
echo "⏳ Attente que les services soient prêts..."
sleep 10

# Installer les dépendances
echo "📦 Installation des dépendances..."
docker compose exec php composer install

# Créer la base de données
echo "🗄️ Création de la base de données..."
docker compose exec php bin/console doctrine:database:create --if-not-exists

# Exécuter les migrations
echo "🔄 Exécution des migrations..."
docker compose exec php bin/console doctrine:migrations:migrate --no-interaction

# Charger les fixtures
echo "📝 Chargement des données de test..."
docker compose exec php bin/console doctrine:fixtures:load --no-interaction

# Créer le dossier d'upload
echo "📁 Création du dossier d'upload..."
docker compose exec php mkdir -p public/uploads/articles
docker compose exec php chmod -R 777 public/uploads

# Vider le cache
echo "🧹 Vidage du cache..."
docker compose exec php bin/console cache:clear

echo ""
echo "🎉 Installation terminée avec succès !"
echo ""
echo "📋 Informations d'accès :"
echo "   Frontend : https://localhost"
echo "   Administration : https://localhost/admin"
echo ""
echo "🔐 Identifiants par défaut :"
echo "   Admin : admin@blog.com / admin123"
echo "   Éditeur : editor@blog.com / editor123"
echo ""
echo "🌐 Langues disponibles :"
echo "   Français : https://localhost/fr/"
echo "   Anglais : https://localhost/en/"
echo "   Espagnol : https://localhost/es/"
echo ""
echo "⚠️  N'oubliez pas de changer les mots de passe par défaut en production !" 