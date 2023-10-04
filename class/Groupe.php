<?php

namespace App;

use PDO;
use PDOException;

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
     * @return boolean
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
     * @return boolean
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
     * @return boolean
     */
    public function supprimerAgentGroupe(int $id_agent, int $id_groupe)
    {
        try {
            $sql = "DELETE FROM appartenance WHERE id_agent = :id_agent AND id_groupe = :id_groupe";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_groupe', $id_groupe, PDO::PARAM_STR);
            $stmt->bindParam(':id_agent', $id_agent, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
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

    /**
     * Crée un nouveau groupe de travail
     * @param int $id_groupe
     * @param string $nom_groupe
     * 
     * @return boolean
     */
    public function ajoutGroupe(int $id_groupe, string $nom_groupe)
    {
        try {
            $sql = "INSERT into groupe(id_groupe, nom_groupe) VALUES(:id_groupe, :nom_groupe) ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_groupe', $id_groupe, PDO::PARAM_INT);
            $stmt->bindParam(':nom_groupe', $nom_groupe, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function supprGroupe(int $id_groupe){
        try{
            $sql = "DELETE FROM appartenance";
        }catch (PDOException $e){

        }
    }

    public function changeNomGroupe(int $id_groupe, string $nom){
        try{
            $sql = "UPDATE groupe SET nom_groupe = :nom WHERE id_groupe = :id_groupe";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_INT);
            $stmt->bindParam(':id_groupe', $id_groupe, PDO::PARAM_STR);
            $stmt->execute();

        }catch (PDOException $e){
            echo 'echec';
        }
    }
}
