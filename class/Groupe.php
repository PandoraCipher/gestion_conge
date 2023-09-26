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
        echo 'Voici la classe qui gère les groupes de travail';
    }

    /**
     * Récupère 
     * @return array
     */
    public function recupGroupe(int $id_groupe, string $nom_groupe = null): array{
        $sql = "SELECT * FROM appartenance WHERE id_groupe = :id_groupe";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_groupe', $id_groupe, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Ajoute un nouvel enregistrement qui relie un agent à un groupe de travail
     * @param int $id_agent
     * @param int $id_groupe
     * 
     * @return [type]
     */
    public function ajoutAgentGroupe(int $id_agent, int $id_groupe){
        $sql = "INSERT into appartennance(id_groupe, id_agent) VALUES(:id_groupe, :id_agent);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_groupe', $id_groupe, PDO::PARAM_STR);
        $stmt->bindParam(':id_agent', $id_agent, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Supprimer un agent d'un groupe de travail en supprimant un enregistrement de la table appartenance
     * @param int $id_agent
     * @param int $id_groupe
     * 
     * @return [type]
     */
    public function supprimerAgentGroupe(int $id_agent, int $id_groupe){
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
    public function changementGroupe(int $id_agent, int $old_groupe, int $new_groupe){
        $this->supprimerAgentGroupe($id_agent, $old_groupe);
        $this->ajoutAgentGroupe($id_agent, $new_groupe);
    }
}
