# 🌾 Skoolis-AGRI - Projet Scolaire

## 📝 Description
**Skoolis-AGRI** est une application web développée dans le cadre d'un projet scolaire. Il s'agit d'un système de gestion pour une coopérative agricole, conçu pour faciliter le suivi et la gestion quotidienne des activités agricoles.

L'objectif de ce projet est de mettre en pratique les concepts de développement web (frontend et backend) ainsi que la gestion de bases de données relationnelles.

## ✨ Fonctionnalités Principales
L'application propose plusieurs modules de gestion (représentés par les formulaires F1 à F5) :
*   🧑‍🌾 **Gestion des Agriculteurs (F1)** : Suivi des membres de la coopérative.
*   🗺️ **Gestion des Parcelles (F2)** : Cartographie et enregistrement des terrains agricoles.
*   🔄 **Cultures et Rotations (F3)** : Planification et suivi des cultures sur les différentes parcelles.
*   🧪 **Gestion des Intrants (F4)** : Suivi des engrais, semences et autres produits utilisés.
*   🚜 **Suivi des Récoltes (F5)** : Enregistrement des rendements et de la production.
*   📊 **Bilan** : Vue globale et statistiques des activités.

## 🛠️ Technologies Utilisées
*   **Frontend** : HTML5, CSS3, Bootstrap 5 (pour un design moderne et responsive).
*   **Backend** : PHP 8.
*   **Base de données** : MySQL (via PDO pour la sécurité et la flexibilité).
*   **Environnement de développement** : XAMPP (Apache/MySQL).

## 🚀 Installation & Utilisation

1.  **Prérequis** : Vous devez avoir un environnement serveur local installé (comme XAMPP, WAMP ou MAMP).
2.  **Cloner le projet** :
    ```bash
    git clone https://github.com/votre-nom-utilisateur/skoolis-agri-sco.git
    ```
3.  **Configuration du dossier** : Placez le dossier du projet dans le répertoire racine de votre serveur web (ex: `htdocs` pour XAMPP).
4.  **Base de données** :
    *   Ouvrez phpMyAdmin.
    *   Créez une base de données nommée `skoolis_agri`.
    *   Importez le fichier `skoolis_agri.sql` fourni à la racine du projet pour créer les tables.
5.  **Configuration de la connexion** :
    *   Si nécessaire, modifiez le fichier `php_app/db.php` pour ajuster les identifiants de connexion à votre base de données locale (par défaut : `root` et le mot de passe est configuré selon votre environnement local).
6.  **Lancement** : Ouvrez votre navigateur et accédez à `http://localhost/skoolis-agri-sco/`.

## 🎓 Contexte du Projet
Ce projet a été réalisé en tant que travail pratique/projet de fin d'étude (à adapter) pour consolider les acquis en conception d'applications web de gestion. L'accent a été mis sur la clarté du code, l'ergonomie de l'interface et la structuration cohérente de la base de données.
