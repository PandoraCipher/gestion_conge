<?php
require_once('connexiondb.php');
if (isset($_SESSION['nom']) && isset($_SESSION['statut'])) {
    $username = $_SESSION['nom'];
    $statut = $_SESSION['statut'];
} else {
    echo "non trouvé!!!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>navbar</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/fontawesome-free-6.2.1-web/css/all.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="container-fluid ">
    <div class="container-fluid custom-container" id="sidebar">
        <div class="">
            <div class="d-flex flex-column justify-content-between col-auto bg-dark min-vh-100">
                <div class="mt-4">
                    <a class="text-white text-decoration-none d-flex ms-3" role="button">
                        <div class="circle"><span id="A">A</span></div>
                        <span class="fs-5 ms-2 d-none d-sm-inline"><?= $statut ?></span>
                    </a>
                    <hr class="text-white d-none d-sm-block">
                    <ul class="nav nav-pills flex-column mt-2 mt-sm-0" id="menu">
                        <li class="nav-item my-sm-1 my-2 disabled">
                            <a class="nav-link text-white text-center text-sm-start" href="Accueil.php" data-bs-toggle="collapse" aria-current="page">
                                <i class="fa fa-house"></i>
                                <span class="ms-2 d-none d-sm-inline">Accueil</span>
                            </a>
                            <ul class="nav collapse ms-1 flex-column d-block" id="sidemenu" data-bs-parent="#menu">
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="Formulaire.php" aria-current="page">Créer</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="Liste.php">Liste</a>
                                </li>
                            </ul>
                        </li>
                        <li class=" nav-item my-1">
                            <a class="nav-link text-white text-center text-sm-start" href="Calendrier.php" aria-current="page">
                                <i class="fa fa-calendar"></i>
                                <span class="ms-0 d-none d-sm-inline">Calendrier</span>
                            </a>
                        </li>
                        <li class="nav-item my-1">
                            <a class="nav-link text-white text-center text-sm-start" href="menu_groupe.php" aria-current="page">
                                <i class="fa fa-users"></i>
                                <span class="ms-2 d-none d-sm-inline">Groupe</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <div class="dropup open">
                        <a class="btn border-none outline-none text-white dropdown-toggle" type="button" id="triggerId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user"></i><span class="ms-2 d-none d-sm-inline"><?= $username ?></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="triggerId">
                            <a class="dropdown-item" href="logout.php">Se déconnecter</a>
                            <a class="dropdown-item" href="#">Paramètre</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="contenu">
        <header>
            Hello world
        </header>

        <main class="custom-container">
            <?= $content; ?>
        </main>

        <!--<footer class=" text-white bg-primary">
            Goodbye Nostalgia
        </footer>-->
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    var statut = "<?= $statut ?>";
    if (statut == "User") {
        document.getElementById("A").innerHTML = "U";
    }
</script>

</html>