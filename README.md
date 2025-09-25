# Gest-Dette
Une application PHP pour gérer les dettes, développée avec MAMP.

## Prérequis
- PHP >= 8.0
- MAMP (ou serveur local équivalent)
- MySQL
- Composer (pour les dépendances)

## Installation
1. Clonez le dépôt : `git clone https://github.com/ajmanhdjj/gest-dette.git`
2. Placez le dossier dans `/Applications/MAMP/htdocs/`.
3. Installez les dépendances : `composer install`
4. Copiez `app/database.example.php` vers `app/database.php` et configurez vos paramètres de base de données (nom de la base, utilisateur, mot de passe).
5. Lancez MAMP et accédez à `http://localhost:8888/gest-dette`.

## Utilisation
- Page principale : `index.php` affiche la liste des dettes.
- Ajouter une dette : `creer-creance.php`.
- Rembourser une dette : `rembourser_creance.php`.
- Recherche : `recherche.php`.

## Fonctionnalités
- Gestion des créances et remboursements.
- Interface responsive avec CSS et JS.
- Recherche de dettes via `recherche.php`.
