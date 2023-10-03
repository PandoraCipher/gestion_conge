<?php

require '../../vendor/autoload.php';

session_start();

use App\Groupe;

require_once('../connexiondb.php');

$groupe = new Groupe($conn);
$id_groupe = $_POST['groupId'];

$noms = $groupe->recupAgentGroupe($id_groupe);
foreach ($noms as $nom) { ?>
    <li class="membre" id="<?= $nom['id_agent'] ?>"><?= $nom['nom'] ?>
        <?php if ($_SESSION['statut'] == 'Admin') { ?>
            <div>
                <button class="btn text-danger mx-1 p-0 btnDel" data-group-id="<?= $id_groupe ?>" data-agent-id="<?= $nom['id_agent'] ?>"><i class="fa fa-trash-alt mx-2"></i></button>
            </div>
        <?php } ?>
    </li>
<?php } ?>