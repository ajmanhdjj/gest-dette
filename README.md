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


## Déploiement sur Render (Docker)
1. Poussez ce dépôt sur GitHub.
2. Sur Render, créez un **Web Service** avec `Runtime = Docker`.
3. Si vous utilisez Blueprint, Render détecte `render.yaml` automatiquement.
4. Renseignez les variables d'environnement (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`, etc.).
5. L'application écoute automatiquement sur `PORT` (injecté par Render).
6. Pour Aiven, configurez au minimum ces variables sur Render:
   - `DB_HOST` = votre hôte `*.aivencloud.com`
   - `DB_PORT` = port Aiven (souvent `17559`)
   - `DB_NAME` = `defaultdb` (ou votre base)
   - `DB_USER` / `DB_PASSWORD` = identifiants Aiven
   - `DB_USE_SSL` = `true`
   - `DB_SSL_CA` = `/var/www/html/database/ca.pem`

### Dépannage
- Erreur `Could not resolve host: github.com` pendant le clone Render : c'est un problème réseau/DNS côté environnement Render (pas lié au Dockerfile). Relancez le déploiement ou vérifiez la connectivité sortante du service.
- Erreur `lstat /opt/render/project/src: no such file or directory` : vérifiez que le service est bien de type **Docker Web Service** et que la racine du repo est `.` (champ `rootDir`).
- Erreur locale `SQLSTATE[HY000] [2002] Connection refused` sous MAMP : utilisez `DB_PORT=8889` (et `DB_HOST=127.0.0.1` ou `localhost`) dans votre `.env`.
