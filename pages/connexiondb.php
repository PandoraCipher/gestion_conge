<?php
$servername = "localhost";
$dbname = "gestion_conge";
$username = "root";
$password = "";

try{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo'Tsy tafiditra ianao: '.$e ->getMessage();
}

?>