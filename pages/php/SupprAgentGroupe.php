<?php

require '../../vendor/autoload.php';

use App\Groupe;

require_once('../connexiondb.php');

$groupe = new Groupe($conn);
$id_groupe = $_POST['groupId'];
$id_agent = $_POST['agentId'];

$result = $groupe->supprimerAgentGroupe($id_agent, $id_groupe);

if ($result) {
    echo $id_agent; // Succès
} else {
    echo "0";
}

?>