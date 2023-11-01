<?php
require('auth.php');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parameter</title>
    <link rel="stylesheet" href="../assets/css/parametre.css">
    <link rel="stylesheet" href="../assets/fontawesome-free-6.2.1-web/css/all.css">
    <link rel="stylesheet" href="../assets/fontawesome-free-6.2.1-web/css/all.css">
    <script src="../assets/js/jquery-3.7.0.js"></script>
</head>

<?php ob_start(); ?>
<body style="background-color: rgb(218, 215, 215);">
<h1 style="padding: 10px;">Paramètre</h1>
    <div class="absence" style="padding: 10px;">
        <h3>Type d'absence</h3>
        <ul>
            <li>congé</li>
            <li>Permission</li>
            <li>maladie</li>
        </ul>
    </div>
    <div class="username" style="padding: 10px;">
        <h3>Changer le nom utilisateur</h3>
        <input type="text" name="newName" id="">
    </div>
    <div class="motdepasse" style="padding: 10px;">
        <h3>Changer le mot de passe</h3>
        <p>Entrer votre mot de passe</p>
        <input type="password" name="amdp">
        <p>Entrer le nouveau mot de passe</p>
        <input type="password" name="nmdp">
    </div>
    <div>
        <p>Paramètre congé</p>
    </div>
    <div>
        <p>Définir permission</p>
    </div>
    
</body>
<?php $content = ob_get_clean();
    require_once('templates/principal.php');
?>


</html>