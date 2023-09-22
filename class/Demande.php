<?php
namespace App;
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
            date_demande";
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
                date_demande";
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
     * 
     * @return [type]
     */
    public function ajoutDemande($date_debut, $date_fin, $type_absence, $motif, $id_agent)
    {
        try {
            $objetDateDebut = DateTime::createFromFormat('d-M-Y', $date_debut);
            $objetDateFin = DateTime::createFromFormat('d-M-Y', $date_fin);
            $date_debut_format = $objetDateDebut->format('Y-m-d');
            $date_fin_format = $objetDateFin->format('Y-m-d');
            $difference = $objetDateDebut->diff($objetDateFin);
            $duree = $difference->days;
            $etat = 'en attente';
            $sql = "INSERT INTO demande(date_debut, date_fin, duree, etat, type_absence, motif, id_agent)
                     VALUES(:date_debut, :date_fin, :duree, :etat, :type_absence, :motif, :id_agent)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':date_debut', $date_debut_format);
            $stmt->bindParam(':date_fin', $date_fin_format);
            $stmt->bindParam(':duree', $duree);
            $stmt->bindParam(':etat', $etat);
            $stmt->bindParam(':type_absence', $type_absence);
            $stmt->bindParam(':motif', $motif);
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
     * @return [type]
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
     * @return [type]
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
     * @return [type]
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

    public function verifDate(DateTime $date_deb, DateTime $date_fin, $id_agent)
    {
        $sql = "SELECT * FROM appartenance WHERE";
        $demande = $this->getDemande($date_deb, $date_fin);
    }
}
