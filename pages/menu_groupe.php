<?php
require '../vendor/autoload.php';

use App\Groupe;

require_once('connexiondb.php');
require 'auth.php';

$groupe = new Groupe($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/groupe.css">
    <link rel="stylesheet" href="../assets/fontawesome-free-6.2.1-web/css/all.css">
</head>

<?php ob_start(); ?>

<body style="background-color: rgb(218, 215, 215);">

    <div class="tableau">
        <h2 class="titre">groupe 1</h2>
        <ul class="">
            <?php $noms = $groupe->recupAgentGroupe(2);
            foreach ($noms as $nom) { ?>
                <li class="membre"><?= $nom['nom'] ?>
                    <div>
                        <button class="btn text-danger mx-1 p-0"><i class="fa fa-trash-alt mx-2"></i></button>
                        <button class="btn text-primary mx-1 p-0"><i class="fa fa-arrow-right mx-2"></i></button>
                    </div>
                </li>
            <?php } ?>
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <select class="custom-select my-2 w-200" name="" id="">
                        <option value="1">Anarana 4</option>
                        <option value="2">Anarana 5</option>
                    </select>
                </div>
                <button class="btn text-success mx-1 p-0"><i class="fa fa-add mx-2"></i></button>
            </div>
        </ul>
    </div>

</body>

<?php $content = ob_get_clean();
require_once('templates/principal.php');
?>

</html>