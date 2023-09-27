<?php
require '../vendor/autoload.php';

use App\Agent;

require_once('connexiondb.php');
session_start();
if (isset($_SESSION['nom']) || isset($_SESSION['statut'])) {
    header('location:Accueil.php'); // Redirige vers index.php si la session n'est pas ouverte
    exit();
} else {
    $message = ' ';
    if (isset($_POST['login'])) {
        $agent = new Agent($conn);
        if ($agent->seConnecter($_POST['nom'], $_POST['mdp'])) {
            $_SESSION['id_agent'] = $agent->id_agent;
            $_SESSION['nom'] = $agent->nom;
            $_SESSION['statut'] = $agent->statut ? "Admin" : "User";

            header('location:Accueil.php');
        } else {
            $message = 'Vérifiez votre nom ou votre mot de passe';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/fontawesome-free-6.2.1-web/css/all.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="form mt-5 ">
        <form method="post" class="needValidation p-2 bg-white rounded-1" style="max-width: 400px; margin: auto;">
            <div class="text-center label">
                <h1>Connexion</h1>
            </div>
            <p class="text-danger"><?= $message; ?></p>
            <div class="Name input-group mb-3 was-validated">
                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                <input type="text" name="nom" class="form-control" id="nom" required>
            </div>
            <div class="pass input-group mb-3 was-validated">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="mdp" class="form-control" id="mdp" required>
                <button type="button" class="btn bg-white border" id="vision"><i class="fa-solid fa-eye"></i></button>
            </div>
            <p><a href="inscription.php" class="">inscription</a></p>
            <div class="fin">
                <input type="submit" class="btn btn-success w-100 my-2" name="login" value="Connexion">
            </div>
        </form>
    </div>
</body>

</html>
<script>
    //Vision
    const motDePasse = document.getElementById('mdp');
    const togglePassword = document.getElementById('vision');

    togglePassword.addEventListener('mousedown', function() {
        motDePasse.type = 'text';
    });
    togglePassword.addEventListener('mouseup', function() {
        motDePasse.type = 'password';
    });
</script>