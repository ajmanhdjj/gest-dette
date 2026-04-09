<?php
require 'app/auth.php';
if (is_authenticated()) {
    header('Location: index.php');
    exit();
}
$error = $_GET['error'] ?? null;
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body class="dashboard">
<div class="authincation section-padding">
  <div class="container h-100">
    <div class="row justify-content-center h-100 align-items-center">
      <div class="col-xl-5 col-md-6">
        <div class="auth-form card">
          <div class="card-body">
            <h4 class="text-center mb-4">Créer un compte</h4>
            <?php if ($error): ?><div class="alert alert-danger">Vérifiez les informations (email déjà utilisé ou mots de passe différents).</div><?php endif; ?>
            <form action="app/signup.php" method="POST">
              <div class="mb-3"><label>Nom complet</label><input type="text" name="nom_complet" class="form-control" required></div>
              <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
              <div class="mb-3"><label>Mot de passe</label><input type="password" name="password" class="form-control" required></div>
              <div class="mb-3"><label>Répéter mot de passe</label><input type="password" name="password_confirm" class="form-control" required></div>
              <button class="btn btn-primary w-100" type="submit">S'inscrire</button>
            </form>
            <p class="mt-3 text-center">Déjà inscrit ? <a href="signin.php">Connexion</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
