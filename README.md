# Gest-Dette
Une application PHP pour gérer les dettes, avec authentification utilisateur.

## Prérequis
- PHP >= 8.0
- MySQL 8+
- MAMP (ou serveur local équivalent)

## Installation
1. Clonez le dépôt.
2. Importez `database/schema.sql` dans MySQL.
3. Copiez `.env.example` vers `.env` puis configurez vos accès MySQL.
4. Pour Aiven, mettez `DB_USE_SSL=true` et gardez `DB_SSL_CA=./database/ca.pem` (ou le chemin de votre certificat).
5. Lancez votre serveur local, puis ouvrez `http://localhost:8888/gest-dette/signin.php`.

## Fonctionnalités
- Inscription : nom complet, email, mot de passe, répétition mot de passe.
- Connexion sécurisée par email + mot de passe hashé.
- Profil utilisateur modifiable (nom + email).
- Déconnexion via menu utilisateur.
- Chaque utilisateur voit uniquement son solde, ses créances et ses transactions (`user_id`).

## Pages
- `signup.php` : inscription.
- `signin.php` : connexion.
- `profile.php` : modifier les informations du compte.
- `index.php` : tableau de bord.
- `solde.php` : historique des transactions et solde.
- `creance.php` : créances et remboursements.
