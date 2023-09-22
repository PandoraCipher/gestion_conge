<?php
// Inclure votre fichier de configuration de base de données et la classe Demande
require_once('../pages/connexiondb.php');
require('Demande.php');

// Vérifier si l'ID de la demande est envoyé via POST
try {
    if (isset($_POST['id_demande'])) {
        $id_demande = $_POST['id_demande'];

        // Créer une instance de la classe Demande
        $demande = new Demande($conn);

        // Appeler la méthode accepterDemande avec l'ID de la demande
        if ($demande->accepterDemande($id_demande)) {
            // La demande a été acceptée avec succès, vous pouvez renvoyer une réponse JSON ou un message
            echo json_encode(["success" => true, "message" => "Demande acceptée avec succès"]);
        } else {
            // Une erreur s'est produite lors de l'acceptation de la demande
            echo json_encode(["success" => false, "message" => "Erreur lors de l'acceptation de la demande"]);
        }
    } else {
        // L'ID de la demande n'est pas défini dans la requête
        echo json_encode(["success" => false, "message" => "ID de demande manquant"]);
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}

