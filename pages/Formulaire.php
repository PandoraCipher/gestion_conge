<?php
require '../vendor/autoload.php';

use App\Demande;

require_once('connexiondb.php');
require 'auth.php';

if (isset($_POST['submit'])) {
    $demande = new Demande($conn);
    $dateD = new DateTime($_POST['dateDeb']);
    $dateF = new DateTime($_POST['dateFin']);
    if ($demande->verifDate($_SESSION['id_agent'], $dateD, $dateF)) {
        $demande->ajoutDemande($_POST['dateDeb'], $_POST['dateFin'], $_POST['type'], $_POST['motif'], $_SESSION['id_agent'], 1);
    } else {
        $demande->ajoutDemande($_POST['dateDeb'], $_POST['dateFin'], $_POST['type'], $_POST['motif'], $_SESSION['id_agent']);
    }

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
    <link rel="stylesheet" href="../assets/css/bootstrap-datepicker.min.css">
    <script src="../assets/js/jquery-3.7.0.js"></script>
    <script src="../assets/js/bootstrap-datepicker.min.js"></script>
    <script src="../assets/js/verifDate.js"></script>
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
        <div class="container-fluid form" style="justify-content: flex-start;" id="formulaire">
            <a href="Accueil.php" class="btn btn-primary rounded-circle position-fixed retour" style="bottom: 20px; right: 20px;">
                <i class="fa fa-arrow-left"></i>
            </a>
            <h2 style="padding: 10px;">Faire une nouvelle demande</h2>
            <div class="mx-3" style="max-width: 500px;">
                <form method="post" id="form-dem" autocomplete="off">
                    <div class="m-1">
                        <label class="form-label"><b>Type de demande:</b></label><br>
                        <div class="input-group">
                            <select class=" input form-select" id="type" name="type">
                                <option value="permission">Permission</option>
                                <option value="conge">Congé</option>
                            </select>
                        </div>
                    </div>
                    <div class="m-2 input-group-date column">
                        <label class="form-label"><b>Date de début:</b></label>
                        <div class=" imput-group-date date">
                            <input class="input" type="text" placeholder="dd-M-Y" name="dateDeb" id="dateDeb" required>
                            <span class="input-group-append">
                                <span class="button-log">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </span>
                        </div>
                    </div>
                    <!-- <p class="text-danger" id="text1" style="display: none;">La date de début ne peut pas être antérieure à la date actuelle.</p> -->
                    <div class="m-2">
                        <label class="form-label"><b>Date de fin:</b></label>
                        <div class=" imput-group-date date">
                            <input class="input" type="text" placeholder="dd-M-Y" name="dateFin" id="dateFin" required>
                            <span class="input-group-append">
                                <span class="button-log">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </span>
                        </div>
                    </div>
                    <!-- <p class="text-danger" id="text2" style="display: none;">La date de fin ne peut pas être antérieure à la date de début.</p> -->
                    <div class="m-2">
                        <label class="form-label"><b>Motif:</b></label><br>
                        <textarea name="motif" class="input" id="motif" cols="30" rows="5" required></textarea>
                    </div>
                    <input class="button-confirm m-2" type="submit" name="submit" value="Vérifier" id="soumission">
                </form>
                <div id="Rem" style="display: none;">
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

    function compteurWeekend(datedeb, datefin) {
        let currentDate = new Date(datedeb);
        var nbrJour = 0;
        while (currentDate <= datefin) {
            const dayOfWeek = currentDate.getDay();
            if (dayOfWeek === 0 || dayOfWeek === 6) {
                nbrJour++;
            }
            currentDate.setDate(currentDate.getDate() + 1);
        }
        return nbrJour;
    }

    var verif = true;

    document.getElementById('soumission').addEventListener('click', function(event) {
        var dateDebut = new Date(document.getElementById('dateDeb').value);
        var dateFin = new Date(document.getElementById('dateFin').value);
        var dateActuelle = new Date();

        var dateDebutSansHeure = new Date(dateDebut.getFullYear(), dateDebut.getMonth(), dateDebut.getDate());
        var dateFinSansHeure = new Date(dateFin.getFullYear(), dateFin.getMonth(), dateFin.getDate());
        var dateActuelleSansHeure = new Date(dateActuelle.getFullYear(), dateActuelle.getMonth(), dateActuelle.getDate());

        var solde = <?= $_SESSION['solde'] ?>;
        var duree = ((dateFinSansHeure - dateDebutSansHeure) / (1000 * 60 * 60 * 24)) - compteurWeekend(dateDebut, dateFin) + 1;

        if (dateDebutSansHeure < dateActuelleSansHeure) {
            alert('La date de début ne peut pas être antérieure à la date actuelle.');
            console.log('dateDebut: ' + dateDebut + ' et dateActuelle: ' + dateActuelle);
            verif = false;
            event.preventDefault(); // Empêche la soumission du formulaire
        } else if (dateFinSansHeure < dateDebutSansHeure) {
            alert('La date de fin ne peut pas être antérieure à la date de début.');
            event.preventDefault(); // Empêche la soumission du formulaire
            verif = false;
        } else if (duree > solde) {
            alert("La durée de la demande dépasse votre solde d'absence.");
            console.log(duree);
            event.preventDefault();
            verif = false;
        }

    });
    document.getElementById('form-dem').addEventListener('submit', function(e){
        alert('demande envoyée');
    });
</script>
<?php $content = ob_get_clean();
require_once('templates/principal.php')
?>

</html>
<?php

?>