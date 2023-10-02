<?php
require '../../vendor/autoload.php';
use App\Groupe;
require_once('../connexiondb.php');

$groupe = new Groupe($conn);

$id_agent = $_POST['selectedValue'];
$id_groupe = $_POST['groupId'];

$result = $groupe->ajoutAgentGroupe($id_agent, $id_groupe);

if ($result) {
    echo 1; // Succès
} else {
    echo "cet Agent est déjà dans ce groupe";
}
?>
