<?php

namespace App;

use PDO;

class Groupe
{
    private $db;

    /**
     * Constructeur
     * @param mixed $db un objet qui permet de se connecter à la base de donnée
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tout les groupes de travail dans la base de donnée
     * @return [type]
     */
    public function recupGroupe()
    {
        $sql = "SELECT * FROM groupe";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Récupère tous les membres d'un groupe et les places dans un tableau $result
     * @return array
     */
    public function recupAgentGroupe(int $id_groupe, string $nom_groupe = null): array
    {
        $sql = "SELECT * FROM agent INNER JOIN appartenance ON agent.id_agent = appartenance.id_agent WHERE appartenance.id_groupe = :id_groupe";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_groupe', $id_groupe, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * vérifie si un agent est déjà associé à un groupe
     * @param int $id_agent
     * @param int $id_groupe
     * 
     * @return [type]
     */
    public function verifAppartenance(int $id_agent, int $id_groupe)
    {
        $sql = "SELECT * FROM appartenance WHERE id_agent = :id_agent AND id_groupe = :id_groupe";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_agent', $id_agent, PDO::PARAM_INT);
        $stmt->bindParam(':id_groupe', $id_groupe, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Ajoute un nouvel enregistrement qui relie un agent à un groupe de travail
     * @param int $id_agent
     * @param int $id_groupe
     * 
     * @return [type]
     */
    public function ajoutAgentGroupe(int $id_agent, int $id_groupe)
    {
        if ($this->verifAppartenance($id_agent, $id_groupe)) {
            return 0;
        } else {
            $sql = "INSERT into appartenance(id_groupe, id_agent) VALUES(:id_groupe, :id_agent);";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_groupe', $id_groupe, PDO::PARAM_INT);
            $stmt->bindParam(':id_agent', $id_agent, PDO::PARAM_INT);
            $stmt->execute();
            return 1;
        }
    }

    /**
     * Supprimer un agent d'un groupe de travail en supprimant un enregistrement de la table appartenance
     * @param int $id_agent
     * @param int $id_groupe
     * 
     * @return [type]
     */
    public function supprimerAgentGroupe(int $id_agent, int $id_groupe)
    {
        $sql = "DELETE FROM appartenance WHERE id_agent = :id_agent AND id_groupe = :id_groupe";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_groupe', $id_groupe, PDO::PARAM_STR);
        $stmt->bindParam(':id_agent', $id_agent, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * permet de changer le groupe de travail d'un agent
     * @param int $id_agent
     * @param int $old_groupe
     * @param int $new_groupe
     * 
     * @return [type]
     */
    public function changementGroupe(int $id_agent, int $old_groupe, int $new_groupe)
    {
        $this->supprimerAgentGroupe($id_agent, $old_groupe);
        $this->ajoutAgentGroupe($id_agent, $new_groupe);
    }
}
