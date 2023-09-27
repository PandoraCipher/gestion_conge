<?php
require '../vendor/autoload.php';
use App\Demande;

require_once('connexiondb.php');
require 'auth.php';


    if (($_SESSION['id_agent'] != "0") && ($_SESSION['statut'] == "User")) {
        $dem = new Demande($conn);
        $demandes = $dem->compteurDemandeAgent($_SESSION['id_agent']);
    } else if (($_SESSION['id_agent'] != "0") && ($_SESSION['statut'] == "Admin")) {
        $dem = new Demande($conn);
        $demandes = $dem->compteurDemande();
    }
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="../assets/fontawesome-free-6.2.1-web/css/all.css">
    </head>

    <?php ob_start(); ?>

    <body style="background-color: rgb(218, 215, 215);">
        <div class="container-fluid">
            <!--page d'accueil-->
            <div class="container-fluid" id="Accueil">
                <h2 style="padding: 10px;">Bienvenue</h2>
                <div id="content">
                    <div class="CL" style="background-color:#1890a0;">
                        <h2>Créer</h2>
                        <p>Faire une demande d'absence</p>
                        <a href="Formulaire.php"><button>Ouvrir <i class="fa fa-arrow-alt-circle-right"></i></button></a>
                    </div>
                    <div class="CL" style="background-color: #18a03a;">
                        <h2>Liste</h2>
                        <p>Voir la liste des demandes</p>
                        <a href="Liste.php"><button>Ouvrir <i class="fa fa-arrow-alt-circle-right"></i></button></a>
                    </div>
                </div>
                <div id="conge" style="background-color:#53f3e6;">
                    <div class="val">
                        <p style="font-size: 50px; margin: 0; padding: 0;"><?= $_SESSION['acquis']; ?></p>
                        <p style="margin: 0; padding: 0;">acquis</p>
                    </div>
                    <div class="val">
                        <p style="font-size: 50px; margin: 0; padding: 0;"><?= $_SESSION['solde']; ?></p>
                        <p style="margin: 0; padding: 0;">solde</p>
                    </div>
                    <div class="val">
                        <p style="font-size: 50px; margin: 0; padding: 0;"><?= ($_SESSION['acquis'] - $_SESSION['solde']); ?></p>
                        <p style="margin: 0; padding: 0;">pris</p>
                    </div>
                </div>
                <div id="notif">
                    <p>Vous avez <?= $demandes ?> demande en attente</p>
                </div>
            </div>
        </div>
    </body>
    <?php $content = ob_get_clean();
    require_once('templates/principal.php');
    ?>

    </html>

<?php

