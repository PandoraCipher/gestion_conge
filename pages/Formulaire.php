<?php
require '../vendor/autoload.php';
use App\Demande;

require_once('connexiondb.php');
require 'auth.php';

    if (isset($_POST['submit'])) {
        $demande = new Demande($conn);
        $demande->ajoutDemande($_POST['dateDeb'], $_POST['dateFin'], $_POST['type'], $_POST['motif'], $_SESSION['id_agent']);
        //header('location:Liste.php');
    }

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulaire</title>
        <!-- Inclure les fichiers CSS et JavaScript de Bootstrap-datepicker -->
        <link rel="stylesheet" href="../source/css/bootstrap-datepicker.min.css">
        <script src="../source/js/jquery-3.7.0.js"></script>
        <script src="../source/js/bootstrap-datepicker.min.js"></script>
        <script>
            window.addEventListener("popstate", function(event) {
                window.location.href = "/Accueil.php";
            });
        </script>
    </head>

    <?php ob_start(); ?>

    <body style="background-color: rgb(218, 215, 215);">
        <div class="container-fluid">
            <!--Formulaire de demande -->
            <div class="container-fluid" style="justify-content: flex-start;" id="formulaire">
                <a href="Accueil.php" class="btn btn-primary rounded-circle position-fixed retour" style="bottom: 20px; right: 20px;">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <h2 style="padding: 11px;">Faire une nouvelle demande</h2>
                <div class="mx-3 py-3" style="max-width: 500px;">
                    <form method="post" id="form-dem" autocomplete="off">
                        <div class="m-1">
                            <label class="form-label">Type de demande:</label><br>
                            <div class="select-wrapper">
                                <select class="form-control" id="type" name="type">
                                    <option value="permission">Permission</option>
                                    <option value="conge">Congé</option>
                                    <i class="fa fa-caret-down"></i>
                                </select>
                            </div>
                        </div>
                        <div class="m-2 input-group-date column">
                            <label class="form-label">Date de début:</label>
                            <div class=" imput-group-date date">
                                <input class="form-control" type="text" placeholder="dd-M-Y" name="dateDeb" id="dateDeb" required>
                                <span class="input-group-append">
                                    <span class="input-group-text bg-white">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="m-2">
                            <label class="form-label">Date de fin:</label>
                            <div class=" imput-group-date date">
                                <input class="form-control" type="text" placeholder="dd-M-Y" name="dateFin" id="dateFin" required>
                                <span class="input-group-append">
                                    <span class="input-group-text bg-white">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="m-2">
                            <label class="form-label">Motif:</label><br>
                            <textarea name="motif" class="form-control" id="motif" cols="30" rows="7" required></textarea>
                        </div>
                        <input class="btn btn-primary m-2" type="submit" name="submit" value="Soumettre" id="soumission">
                    </form>
                    <div>
                        <p style="color: red;">Cette demande chevauche 1 personne de votre groupe de travail</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        $(function() {
            $('.date').datepicker({
                format: 'dd-M-yyyy',
                autoclose: true
            });
        })

        document.getElementById('form-dem').addEventListener('submit', function(event) {
            var dateDebut = new Date(document.getElementById('dateDeb').value);
            var dateFin = new Date(document.getElementById('dateFin').value);
            var dateActuelle = new Date();

            if (dateDebut < dateActuelle) {
                alert("La date de début ne peut pas être antérieure à la date actuelle.");
                event.preventDefault(); // Empêche la soumission du formulaire
            } else if (dateFin < dateDebut) {
                alert("La date de fin ne peut pas être antérieure à la date de début.");
                event.preventDefault(); // Empêche la soumission du formulaire
            } else {
                alert('une demande a été envoyée.');
            }
        });
    </script>
    <?php $content = ob_get_clean();
    require_once('templates/principal.php')
    ?>

    </html>
<?php

?>