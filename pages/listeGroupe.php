<?php

require '../vendor/autoload.php';

use App\Groupe;

require_once('connexiondb.php');

$groupe = new Groupe($conn);
$id_groupe = $_POST['groupId'];

$noms = $groupe->recupAgentGroupe($id_groupe);
foreach ($noms as $nom) { ?>
    <li class="membre" id="<?= $nom['id_agent'] ?>"><?= $nom['nom'] ?>
        <div>
            <button class="btn text-danger mx-1 p-0 btnDel" data-group-id="<?= $id_groupe ?>" data-agent-id="<?= $nom['id_agent'] ?>"><i class="fa fa-trash-alt mx-2"></i></button>
            <button class="btn text-primary mx-1 p-0"><i class="fa fa-arrow-right mx-2"></i></button>
        </div>
    </li>
<?php } ?>
