<?php
session_start();

if (!isset($_SESSION['nom']) || !isset($_SESSION['statut'])) {
    header('location:../index.php'); // Redirige vers index.php si la session n'est pas ouverte
    exit();
}
?>
