<?php
require '../vendor/autoload.php';
use App\Groupe;

require_once('connexiondb.php');
require 'auth.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<?php ob_start(); ?>

<body style="background-color: rgb(218, 215, 215);">
<table class="m-4" border="1">
        <thead>
            <tr>
                <th>groupe de travail 1</th>
                <th>groupe de travail 2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Antsonantenaina</td>
                <td>Antsonantenaina</td>
            </tr>
            <tr>
                <td>Anarana</td>
                <td>Anarana 2</td>
            </tr>
            <tr>
                <td>Anarana 2</td>
            </tr>
        </tbody>
    </table>
    
</body>

<?php $content = ob_get_clean();
    require_once('templates/principal.php');
    ?>

</html>