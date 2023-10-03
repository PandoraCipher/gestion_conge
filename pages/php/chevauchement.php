<?php
require '../../vendor/autoload.php';
use App\Demande;
require_once('../connexiondb.php');
session_start();

$date_deb = $_POST['dateDebut'];
$date_fin = $_POST['dateFin'];
$date_debut_obj = DateTime::createFromFormat('d-M-Y', $date_deb);
$date_fin_obj = DateTime::createFromFormat('d-M-Y', $date_fin);

$id = $_SESSION['id_agent'];

$demande = new Demande($conn);
$result = $demande->verifDate($id, $date_debut_obj, $date_fin_obj);

if($result){
    echo 'afficher';
}else{
    echo 'masquer';
}

?>