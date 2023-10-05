<?php

namespace App;

use DateInterval;
use DatePeriod;
use DateTime;
use PDO;
use PDOException;

/**
 * Permet de gérer les demandes(Ajout, récupération, mise à jour)
 */
class Demande
{
    /**
     * permet d'entrer dans la base de donnée
     * @var object
     */
    private $db;

    /**
     * Constructeur de la classe et initialisation de la base de $db
     * @param object $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param int $id_agent
     * récupère toutes les demandes associées à un id
     * @return [type]
     */
    public function recupDemandeAgent($id_agent)
    {
        try {
            $sql = "SELECT * FROM demande WHERE id_agent = :id_agent ORDER BY
             CASE
                WHEN etat = 'en attente' THEN 1 
                ELSE 2 
            END,
            date_demande DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id_agent", $id_agent, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'tsisy hita';
        }
    }

    /**
     * récupère toutes les demandes de la base de donnée
     * @return [type]
     */
    public function recupDemande()
    {
        try {
            $sql = "SELECT * FROM demande ORDER BY 
                CASE 
                    WHEN etat = 'en attente' THEN 1 ELSE 2 
                END,
                date_demande DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'tsisy hita';
        }
    }

    /**
     * compte le nombre de demande en attente et le renvoie
     * @return int
     */
    public function compteurDemande(): int
    {
        try {
            $sql = "SELECT * FROM demande WHERE etat = 'en attente'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return count($result);
        } catch (PDOException $e) {
            echo 'tsisy hita';
            return 0;
        }
    }

    /**
     * compte le nombre de demandes encore en attente d'un agent et le renvoie
     * @param int $id_agent
     * 
     * @return int
     */
    public function compteurDemandeAgent(int $id_agent): int
    {
        try {
            $sql = "SELECT * FROM demande WHERE etat = 'en attente' AND id_agent = :id_agent";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_agent', $id_agent, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return count($result);
        } catch (PDOException $e) {
            echo 'tsisy hita';
            return 0;
        }
    }

    /**
     * Ajoute une nouvelle demande dans la base de donnée
     * @param string $date_debut
     * @param string $date_fin
     * @param string $type_absence
     * @param string $motif
     * @param int $id_agent
     * @param int $chevauchement
     * 
     * @return void
     */
    public function ajoutDemande($date_debut, $date_fin, $type_absence, $motif, $id_agent, $chevauchement = 0)
    {
        try {
            $objetDateDebut = DateTime::createFromFormat('d-M-Y', $date_debut);
            $objetDateFin = DateTime::createFromFormat('d-M-Y', $date_fin);
            // Comptez les samedis et dimanches dans la période de congé
            $interval = new DateInterval('P1D'); // Interval d'un jour
            $periode = new DatePeriod($objetDateDebut, $interval, $objetDateFin);
            $joursWeekend = 0;

            foreach ($periode as $jour) {
                // Utilisez N pour obtenir le numéro du jour de la semaine (1 pour lundi, 7 pour dimanche)
                $jourSemaine = $jour->format('N');

                // Si c'est un samedi (6) ou un dimanche (7), augmentez le compteur
                if ($jourSemaine == 6 || $jourSemaine == 7) {
                    $joursWeekend++;
                }
            }
            $date_debut_format = $objetDateDebut->format('Y-m-d');
            $date_fin_format = $objetDateFin->format('Y-m-d');
            $difference = $objetDateDebut->diff($objetDateFin);
            $duree = $difference->days - $joursWeekend + 1;
            $etat = 'en attente';
            $sql = "INSERT INTO demande(date_debut, date_fin, duree, etat, type_absence, motif, chevauchement, id_agent)
                     VALUES(:date_debut, :date_fin, :duree, :etat, :type_absence, :motif, :chevauchement, :id_agent)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':date_debut', $date_debut_format);
            $stmt->bindParam(':date_fin', $date_fin_format);
            $stmt->bindParam(':duree', $duree);
            $stmt->bindParam(':etat', $etat);
            $stmt->bindParam(':type_absence', $type_absence);
            $stmt->bindParam(':motif', $motif);
            $stmt->bindParam(':chevauchement', $chevauchement);
            $stmt->bindParam(':id_agent', $id_agent);
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'tsy tafiditra: ' . $e->getMessage();
        }
    }

    /**
     * met à jour l'état d'une demande(acceptée) dans la base de donnée en cherchant son id
     * @param int $id_demande
     * 
     * @return void
     */
    public function accepterDemande($id_demande)
    {
        try {
            $etat = 'acceptée';
            $sql = "UPDATE demande SET etat = :etat WHERE id_demande = :id_demande";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':etat', $etat, PDO::PARAM_STR);
            $stmt->bindParam(':id_demande', $id_demande);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo 'tsy niova: ' . $e->getMessage();
        }
    }

    /**
     * met à jour l'état d'une demande(acceptée) dans la base de donnée en cherchant son id
     * @param int $id_demande
     * @param string $motif_rejet défini comme 'null' par défaut
     * 
     * @return void
     */
    public function refuserDemande($id_demande, $motif_rejet = null)
    {
        try {
            $etat = 'refusée';
            $sql = "UPDATE demande SET etat = :etat, motif_rejet = :motif_rejet WHERE id_demande = :id_demande";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':etat', $etat, PDO::PARAM_STR);
            $stmt->bindParam(':motif_rejet', $motif_rejet, PDO::PARAM_STR);
            $stmt->bindParam(':id_demande', $id_demande);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo 'tsy niova: ' . $e->getMessage();
        }
    }

    /**
     * supprime une demande de la base de donnée
     * @param int $id_demande
     * 
     * @return boolean
     */
    public function suprDemande(int $id_demande){
        try{
            $sql = "DELETE FROM demande WHERE id_demande = :id_demande";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id_demande', $id_demande, PDO::PARAM_INT);
            $stmt->execute();
            return true;

        }catch (PDOException $e){
            return false;
        }
    }

    public function recupDateConge($start, $end)
    {
    }

    /**
     * Récupère toutes les dates d'absence(acceptée) durant le mois
     * la date $start représente le début du mois et la date $end représente la fin du mois
     * @param DateTime $start
     * @param Datetime $end
     * retourne ensuite toutes les demandes dans un tableau
     * @return array
     */
    public function getDemande(DateTime $start, Datetime $end): array
    {
        $etat = 'acceptée';
        $sql = "SELECT * FROM demande WHERE (date_debut BETWEEN '{$start->format('Y-m-d')}' AND '{$end->format('Y-m-d')}') AND etat = :etat";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':etat', $etat, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    /**
     * utilise la fonction getDemande() pour récupérer les dates d'absences du mois
     * @param DateTime $start
     * @param Datetime $end
     * retourne ensuite un tableau indexée selon les dates journalières: les absences d'un même jour seront stockées dans le même index
     * @return array
     */
    public function getDemandeByDay(DateTime $start, Datetime $end): array
    {
        $events = $this->getDemande($start, $end);
        $days = [];

        foreach ($events as $event) {
            $startDate = new DateTime($event['date_debut']);
            $endDate = (new DateTime($event['date_debut']))->modify('+' . $event['duree'] . ' days');

            $currentDate = $startDate;

            while ($currentDate <= $endDate) {
                $currentDateString = $currentDate->format('Y-m-d');
                if (!isset($days[$currentDateString])) {
                    $days[$currentDateString] = [$event];
                } else {
                    $days[$currentDateString][] = $event;
                }

                // Passer au jour suivant
                $currentDate->modify('+1 day');
            }
        }

        return $days;
    }

    /**
     * Récupère le nom de celui qui a anvoyé la demande en utilisant l'id_agent contenu dans la demande
     * @param mixed $id
     * 
     * @return string
     */
    public function recup_nom($id)
    {
        include('connexiondb.php');
        $sql = "SELECT agent.nom FROM agent INNER JOIN demande ON agent.id_agent = demande.id_agent WHERE demande.id_agent = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT) .
            $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['nom'];
    }

    /**
     * Vérifie si les dates entrées dans le formulaire se chevauche avec les dates de demandes déjà acceptées des agents du même groupe
     * @param int $id_agent
     * @param DateTime $date_debut
     * @param DateTime $date_fin
     * 
     * @return boolean
     */
    public function verifDate(int $id_agent, DateTime $date_debut, DateTime $date_fin)
    {
        $sql = "SELECT * FROM appartenance WHERE id_agent = :id_agent";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_agent', $id_agent, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $result) {
            $group = new Groupe($this->db);
            $results2 = $group->recupAgentGroupe($result['id_groupe']);
            foreach ($results2 as $result2) {
                $etat = 'acceptée';
                $id = $result2['id_agent'];
                $dateDebutFormatted = $date_debut->format('Y-m-d');
                $dateFinFormatted = $date_fin->format('Y-m-d');
                $sql2 = "SELECT * FROM demande WHERE id_agent = :id AND etat = :etat AND(
                    (:dateDebut BETWEEN date_debut AND date_fin) 
                    OR (:dateFin BETWEEN date_debut AND date_fin)
                    OR (date_debut BETWEEN :dateDebut AND :dateFin)
                    OR (date_fin BETWEEN :dateDebut AND :dateFin)
                    )";
                $stmt2 = $this->db->prepare($sql2);
                $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt2->bindParam(':etat', $etat, PDO::PARAM_STR);
                $stmt2->bindParam(':dateDebut', $dateDebutFormatted, PDO::PARAM_STR);
                $stmt2->bindParam(':dateFin', $dateFinFormatted, PDO::PARAM_STR);
                $stmt2->execute();
                $results3 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($results3)) {
                    return true; // Il y a un chevauchement
                }
            }
        }
        return false;
    }
}
