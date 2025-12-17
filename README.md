---

# GestionLieux - Application Symfony pour la Gestion des Lieux

**GestionLieux** est une application web complète destinée à la gestion et au suivi des lieux. Cette solution permet aux utilisateurs de **créer, consulter, modifier et supprimer des lieux**, tout en offrant une structure prête pour des fonctionnalités avancées telles que la recherche, les filtres et la gestion des utilisateurs.

**Dépôt GitHub** : [https://github.com/ZaynebAKR/Symfony](https://github.com/ZaynebAKR/Symfony)
**Branche de développement** : `main`

## ✨ Fonctionnalités Principales

### 📌 Gestion des Lieux

* **CRUD complet** : création, lecture, modification et suppression des lieux
* **Fiches détaillées** avec informations comme le nom, l’adresse et la description
* **Interface claire et intuitive** pour visualiser et gérer les lieux

### 👥 Gestion des Utilisateurs *(optionnelle selon l’implémentation)*

* **Authentification** sécurisée via Symfony Security
* **Profils utilisateurs** pour différents rôles (admin, visiteur, gestionnaire)
* **Permissions** pour contrôler l’accès à certaines fonctionnalités

### 🗂 Organisation et Navigation

* **Recherche et filtres** pour retrouver rapidement des lieux
* **Tableaux de bord** pour visualiser les lieux et leurs informations clés
* **Frontend dynamique** grâce à Twig et JavaScript

## 🛠️ Stack Technique

* **Framework** : [Symfony](https://symfony.com/) (PHP MVC)
* **Base de données** : MySQL ou PostgreSQL avec Doctrine ORM
* **Templating** : [Twig](https://twig.symfony.com/)
* **Frontend** : HTML, CSS/SCSS, JavaScript
* **Tests** : PHPUnit pour tests unitaires et fonctionnels

## 📋 Installation Locale

### Prérequis

* PHP 8+
* Composer
* Base de données MySQL/PostgreSQL
* Terminal avec git

### Étapes d'installation

1. **Cloner le projet depuis votre dépôt**

```bash
git clone https://github.com/ZaynebAKR/Symfony.git
cd Symfony
```

2. **Installer les dépendances**

```bash
composer install
```

3. **Copier le fichier d’environnement**

```bash
cp .env .env.local
```

4. **Configurer la base de données** dans `.env.local`

5. **Exécuter les migrations**

```bash
php bin/console doctrine:migrations:migrate
```

6. **Lancer le serveur de développement**

```bash
symfony serve
```

