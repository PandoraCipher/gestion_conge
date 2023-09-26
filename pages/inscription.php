<?php

require '../vendor/autoload.php';
use App\Agent;

require_once('connexiondb.php');

$message = ' ';

if ((isset($_POST['signin'])) && ($_POST['mdp'] == $_POST['conf_mdp'])){
    $mdphache = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
    $agent = new Agent($conn);
    $agent->ajoutAgent($_POST['id_agent'], $_POST['username'], $mdphache);

    header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../source/fontawesome-free-6.2.1-web/css/all.css">
    <link rel="stylesheet" href="../source/css/bootstrap.css">
    <script src="../source/js/bootstrap.bundle.min.js"></script>
</head>

<body class="log">
    <div class="form mt-5">
        <form method="post" class="needValidation container-fluid bg-white rounded-1" style="max-width: 400px; margin: auto;">
            <div class="text-center label">
                <h1>Connexion</h1>
            </div>
            <div class="Name input-group mb-3 was-validated">
                <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                <input type="text" placeholder="Id" name="id_agent" class="form-control" id="id_agent" required>
            </div>
            <div class="Name input-group mb-3 was-validated">
                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                <input type="text" placeholder="Username" name="username" class="form-control" id="username" required>
            </div>
            <div class="pass input-group mb-3 was-validated">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" placeholder="Mot de passe" name="mdp" class="form-control" id="mdp" required>
                <button type="button" class="btn bg-white border" id="vision"><i class="fa-solid fa-eye"></i></button>
            </div>
            <div class="pass input-group mb-3 was-validated">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" placeholder="Confirmer" name="conf_mdp" class="form-control" id="conf_mdp" required>
            </div>
            <div class="fin">
                <input type="submit" class="btn btn-success w-100 my-2" name="signin" value="inscription">
            </div>
        </form>
    </div>
</body>

</html>
<script>
    // Sélectionnez l'élément d'entrée du mot de passe
    var motdepasse = document.getElementById('mdp');

    // Écoutez l'événement de changement dans l'entrée du mot de passe
    motdepasse.addEventListener('input', function() {
        // Récupérez la valeur du mot de passe
        var password = motdepasse.value;

        // Vérifiez si le mot de passe contient au moins un chiffre, une majuscule et une minuscule
        var hasNumber = /\d/.test(password);
        var hasUppercase = /[A-Z]/.test(password);
        var hasLowercase = /[a-z]/.test(password);

        // Vérifiez si le mot de passe satisfait les critères requis
        if (hasNumber && hasUppercase && hasLowercase) {
            motdepasse.setCustomValidity('');
        } else {
            motdepasse.setCustomValidity('Le mot de passe doit contenir au moins un chiffre, une majuscule et une minuscule.');
        }
    });

    var password2Input = document.getElementById('conf_mdp');
    password2Input.addEventListener('input', function(){
      var password2 = password2Input.value;
      var password = motdepasse.value;
 
      if (password === password2){
        password2Input.setCustomValidity('');
      }
      else{
        password2Input.setCustomValidity('Le mot de passe doit être identique au precédent');
      }
    } );

        //Vision
        const motDePasse = document.getElementById('mdp');
    const togglePassword = document.getElementById('vision');

    togglePassword.addEventListener('click', function() {
        if (motDePasse.type === 'password') {
            motDePasse.type = 'text';
        } else {
            motDePasse.type = 'password';
        }
    });
</script>