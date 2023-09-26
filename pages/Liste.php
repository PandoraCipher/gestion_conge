<?php
require '../vendor/autoload.php';
use App\Demande;

require_once('connexiondb.php');
require 'auth.php';

    /**
     * récupère un nom dans la table agent grâce à l'id_agent contenu dans l'enregistrement de la demande
     * @param int $id
     * 
     * @return string
     */
    function recup_nom($id)
    {
        include('connexiondb.php');
        $sql2 = "SELECT agent.nom FROM agent INNER JOIN demande ON agent.id_agent = demande.id_agent WHERE demande.id_agent = :id";
        $stmt = $conn->prepare($sql2);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT) .
            $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nom'];
    }

    if (($_SESSION['id_agent'] != "0") && ($_SESSION['statut'] == "User")) {
        $dem = new Demande($conn);
        $demandes = $dem->recupDemandeAgent($_SESSION['id_agent']);
    } else if (($_SESSION['id_agent'] != "0") && ($_SESSION['statut'] == "Admin")) {
        $dem = new Demande($conn);
        $demandes = $dem->recupDemande();
    }

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Liste</title>
        <script>
            window.addEventListener("popstate", function(event) {
                window.location.href = "/Accueil.php";
            });
        </script>
    </head>

    <?php ob_start(); ?>

    <body style="background-color: rgb(218, 215, 215);">
        <div class="container-fluid">
            <!-- Affichage de la liste des demandes -->
            <div class="container-fluid" id="formulaire">
                <a href="Accueil.php" class="btn btn-primary rounded-circle position-fixed retour" style="bottom: 20px; right: 20px;">
                    <i class="fa fa-arrow-left"></i>
                </a>
                <h2 style="padding: 10px;">Liste des demandes</h2>
                <div class="myscrollbox">
                    <?php
                    foreach ($demandes as $demande) {
                        if (isset($_POST['accept'])) {
                            $dem->accepterDemande($_POST['id_demande']);
                            header('location:Liste.php');
                        }
                        if (isset($_POST['refus'])) {
                            $dem->refuserDemande($_POST['id_demande'], $_POST['motif_rejet']);
                            header('location:Liste.php');
                        }
                    ?>
                        <form class="container" method="post">
                            <div class="container-fluid liste">
                                <div class="info">
                                    <p><?php
                                        if ($_SESSION['statut'] == "User") {
                                            echo $_SESSION['nom'];
                                        } else {
                                            echo recup_nom($demande['id_agent']);
                                        }
                                        
                                        $date1 = new DateTime($demande['date_debut']);
                                        $date2 = new DateTime($demande['date_fin']);
                                        $date_deb = $date1->format("d M Y");
                                        $date_fin = $date2->format("d M Y");
                                        ?></p>
                                    <p><?= $demande['type_absence']; ?></p>
                                    <p><?= $date_deb . " au " . $date_fin; ?></p>
                                </div>
                                <div class="detail">
                                    <a href="#" class="detail" id="detail<?= $demande['id_demande']; ?>">détails</a>
                                    <div class="pop pop<?= $demande['id_demande']; ?>" style="display: none">
                                        <p style="font-weight: bold;"><u>Voici les détails de cette demande</u></p>
                                        <p>Envoyé le: <?= $demande['date_demande'] ?></p>
                                        <p>par: <?= recup_nom($demande['id_agent']) ?></p>
                                        <p>date début: <?= $date_deb ?></p>
                                        <p>date fin: <?= $date_fin ?></p>
                                        <p>durée: <?= $demande['duree'] ?> jours</p>
                                        <p>Motif: <?= $demande['motif'] ?></p>
                                        <p><?php
                                            if ($demande['motif_rejet'] != "") {
                                                echo "Motif de rejet: " . $demande['motif_rejet'];
                                            }
                                            ?></p>
                                        <input class="btn btn-primary" name="ok" id="ok<?= $demande['id_demande']; ?>" type="button" value="OK">
                                    </div>
                                    <div class="etat">
                                        <?php
                                        if ($_SESSION['statut'] == 'User') {
                                            if ($demande['etat'] == "acceptée") {
                                                echo '<p class="text-success">' . $demande['etat'] . "</p>";
                                            } else if ($demande['etat'] == "refusée") {
                                                echo '<p class="text-danger">' . $demande['etat'] . "</p>";
                                            } else {
                                                echo '<p class="text-primary">' . $demande['etat'] . "</p>";
                                            }
                                        } else {
                                            if ($demande['etat'] == 'en attente') {
                                        ?>
                                                <input type="hidden" name="id_demande" value="<?= $demande['id_demande']; ?>">
                                                <input class="form-control" type="text" placeholder="motif de rejet" name="motif_rejet" id="motif_rejet<?= $demande['id_demande']; ?>">
                                                <input class="btn btn-success m-1" type="submit" name="accept" id="accept" value="Accepter" onclick="alert('demande acceptée')">
                                                <input class="btn btn-danger m-1" type="submit" name="refus" id="refus<?= $demande['id_demande']; ?>" value="Refuser">

                                        <?php } else {
                                                if ($demande['etat'] == "acceptée") {
                                                    echo '<p class="text-success">' . $demande['etat'] . "</p>";
                                                } else {
                                                    echo '<p class="text-danger">' . $demande['etat'] . "</p>";
                                                }
                                            }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </form>

                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
    <?php $content = ob_get_clean();
    require_once('templates/principal.php')
    ?>

    <script>
        var statut = "<?= $_SESSION['statut'] ?>";
        var etat = "<?=$demande['etat'] ?>"
        document.addEventListener('DOMContentLoaded', function() {
            if ((statut == 'Admin') && (etat == 'en attente')) {
                const refuser<?= $demande['id_demande']; ?> = document.getElementById('refus<?= $demande['id_demande']; ?>');
                const motifRejet<?= $demande['id_demande']; ?> = document.getElementById('motif_rejet<?= $demande['id_demande']; ?>');
                refuser<?= $demande['id_demande']; ?>.addEventListener('click', function() {
                    if (motifRejet<?= $demande['id_demande']; ?>.value === "") {
                        event.preventDefault();
                        alert('Veuillez remplir le motif de rejet.');
                    } else {
                        alert('demande refusée');
                    }
                })
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            <?php foreach ($demandes as $demande) { ?>
                const lienDetail<?= $demande['id_demande']; ?> = document.getElementById('detail<?= $demande['id_demande']; ?>');
                const maDiv<?= $demande['id_demande']; ?> = document.querySelector('.pop<?= $demande['id_demande']; ?>');
                const okButton<?= $demande['id_demande']; ?> = document.getElementById('ok<?= $demande['id_demande']; ?>');

                lienDetail<?= $demande['id_demande']; ?>.addEventListener('click', function(event) {
                    event.preventDefault();
                    maDiv<?= $demande['id_demande']; ?>.style.display = 'block';
                });

                okButton<?= $demande['id_demande']; ?>.addEventListener('click', function() {
                    maDiv<?= $demande['id_demande']; ?>.style.display = 'none';
                });
            <?php } ?>
        });
    </script>

    </html>
