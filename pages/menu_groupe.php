<?php
require '../vendor/autoload.php';

use App\Agent;
use App\Groupe;

require_once('connexiondb.php');
require 'auth.php';

$groupe = new Groupe($conn);
$ag = new Agent($conn);
$Allagent = $ag->recupToutAgent();
$Allgroup = $groupe->recupGroupe();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>groupe</title>
    <link rel="stylesheet" href="../assets/css/groupe.css">
    <link rel="stylesheet" href="../assets/fontawesome-free-6.2.1-web/css/all.css">
    <script src="../assets/js/jquery-3.7.0.js"></script>
</head>

<?php ob_start(); ?>

<body style="background-color: rgb(218, 215, 215);">
    <a href="Accueil.php" class="btn btn-primary rounded-circle position-fixed retour" style="bottom: 20px; right: 20px;">
        <i class="fa fa-arrow-left"></i>
    </a>
    <h2 style="padding: 10px;">Groupes de travail</h2>
    <?php foreach ($Allgroup as $group) { ?>

        <div class="tableau">
            <div class="titre" style="display: flex; justify-content: space-between;">
                <h2><?= $group['nom_groupe'] ?></h2>
                <button class="btn btnShow" data-group-id="<?= $group['id_groupe'] ?>" id="show<?= $group['id_groupe'] ?>">show</button>
            </div>
            <ul  id="data<?= $group['id_groupe'] ?>">

            </ul>
            <?php if ($_SESSION['statut'] == 'Admin') { ?>
                <div style="display: flex; justify-content: space-between;">
                    <div>
                        <select class="custom-select m-2 myscroll " name="" id="selectAgent<?= $group['id_groupe'] ?>">
                            <?php foreach ($Allagent as $agent) { ?>
                                <option value="<?= $agent['id_agent']; ?>"><?= $agent['nom']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button class="btn text-success mx-1 p-0 btnAdd" data-group-id="<?= $group['id_groupe'] ?>"><i class="fa fa-plus mx-2"></i></button>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    <script src="../assets/js/groupe.js"></script>
</body>

<?php $content = ob_get_clean();
require_once('templates/principal.php');

?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php foreach ($Allgroup as $group) { ?>
            const show<?= $group['id_groupe'] ?> = document.getElementById("show<?= $group['id_groupe'] ?>");
            show<?= $group['id_groupe'] ?>.addEventListener('click', function(event) {
                show<?= $group['id_groupe'] ?>.innerHTML = " ";
            })

        <?php } ?>

    })
</script>

</html>