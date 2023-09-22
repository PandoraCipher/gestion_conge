<?php
require '../vendor/autoload.php';
use App\Date\Month;
use App\Demande;

require_once('connexiondb.php');
require 'auth.php';

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Calendrier</title>
        <link rel="stylesheet" href="../source/css/bootstrap.css">
        <link rel="stylesheet" href="../source/css/calendar.css">
        <script>
            window.addEventListener("popstate", function(event) {
                window.location.href = "/Accueil.php";
            });
        </script>
    </head>

    <?php ob_start(); ?>

    <body style="background-color: rgb(218, 215, 215);">
        <?php
        $dem1 = new Demande($conn);
        $dem = new Demande($conn);
        $month = new Month($_GET['month'] ?? null, $_GET['year'] ?? null);
        $start = $month->getStartingDay();
        $start = $start->format('N') === '1' ? $start : $month->getStartingDay()->modify('last monday');
        $weeks = $month->getWeeks();
        $end = (clone $start)->modify('+' . (6 + 7 * ($weeks - 1)) . 'days');
        $dem = $dem->getDemandeByDay($start, $end);
        ?>
        <a href="Accueil.php" class="btn btn-primary rounded-circle position-fixed retour" style="bottom: 20px; right: 20px;">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div class="d-flex flex-row align-item-center justify-content-between m-2">
            <h1><?= $month->toString(); ?></h1>
            <div>
                <a href="Calendrier.php?month=<?= $month->previousMonth()->month ?>&year=<?= $month->previousMonth()->year; ?>" class="btn btn-primary">&lt;</a>
                <a href="Calendrier.php?month=<?= $month->nextMonth()->month ?>&year=<?= $month->nextMonth()->year; ?>" class="btn btn-primary">&gt;</a>
            </div>
        </div>

        <div class="calendar__scrollbox m-2" style="background-color: #fff;">
            <table class="calendar__table">
                <?php for ($i = 0; $i < $weeks; $i++) : ?>
                    <tr>
                        <?php
                        foreach ($month->days as $k => $day) :
                            $date = (clone $start)->modify("+" . ($k + $i * 7) . "days");
                            if ($date->format('N') == 6 || $date->format('N') == 7) {
                                $demForDay = []; // Vide le tableau $demForDay pour ces jours
                            } else {
                                $demForDay = $dem[$date->format('Y-m-d')] ?? [];
                            }
                        ?>
                            <td class="<?= $month->withinMonth($date) ? '' : 'calendar__othermonth' ?>">
                                <?php if ($i == 0) { ?>
                                    <div class="calendar__weekday"><?= $day; ?></div>
                                <?php } ?>
                                <div class="calendar__day"><?= $date->format('d'); ?></div>
                                <?php foreach ($demForDay as $demande) {
                                    $dateDebut = new DateTime($demande['date_debut']);
                                    $dateFin = new DateTime($demande['date_fin']);
                                    if ($date >= $dateDebut && $date <= $dateFin) {
                                ?>
                                        <div class="calendar__event">
                                            <?= $dem1->recup_nom($demande['id_agent']) ?>
                                        </div>
                                <?php
                                    }
                                } ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        </div>

    </body>
    <?php $content = ob_get_clean();
    require_once('templates/principal.php');
    ?>

    </html>
