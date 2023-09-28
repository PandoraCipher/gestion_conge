<?php

require '../vendor/autoload.php';

use App\Groupe;

require_once('connexiondb.php');

$id_groupe = $_POST['groupeID'];

$noms = $groupe->recupAgentGroupe($id_groupe);
foreach ($noms as $nom) { ?>
    <li class="membre"><?= $nom['nom'] ?>
        <div>
            <button class="btn text-danger mx-1 p-0"><i class="fa fa-trash-alt mx-2"></i></button>
            <button class="btn text-primary mx-1 p-0"><i class="fa fa-arrow-right mx-2"></i></button>
        </div>
    </li>
<?php } ?>

?>