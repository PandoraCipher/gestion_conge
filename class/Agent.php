<?php
namespace App;

use PDO;
use PDOException;

/**
 * Permet de faire des opérations sur des agents dans la base de donnée
 */
class Agent{
    /**
     * permet de se conecter à une base de donnée
     * @var object
     */
    private $db;

    /**
     * Contient l'id d'un agent
     * @var int
     */
    public $id_agent;

    /**
     * adresse mail
     * @var string
     */
    public $mail;

    /**
     * @var string
     */
    public $nom;

    /**
     * 
     * @var int
     */
    public $statut;




    /**
     * constructeur
     * défini la base de donnée à utiliser
     * @param object $db
     */
    public function __construct($db){
        $this->db = $db;
    }
    
    /**
     * seConnecter
     *
     * @param  string $nom
     * @param  string $mdp
     * @return [type]
     */
    public function seConnecter($nom, $mdp){
        try{
            $sql = "SELECT * FROM agent WHERE nom = :nom OR mail = :nom";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user){
                if((password_verify($mdp, $user['mdp']))){
                $this->id_agent = $user['id_agent'];
                $this->nom = $user['nom'];
                $this->mail = $user['mail'];
                $this->statut = $user['statut'];
                return true;
                }else{
                    return false;
                }
            }
        }catch(PDOException $e){
            echo "tsita";
            return false;
        }
    }

    /**
     * récupère un enregistrement d'agent
     * @param int $id_agent
     * 
     * @return [type]
     */
    public function recupAgent(int $id_agent){
        try{
            $sql = "SELECT * FROM agent WHERE id_agent = :id_agent";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id_agent", $id_agent, PDO::PARAM_INT);
            $stmt->execute();
            return $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo "erreur" . $e->getMessage();
        }
    }

    /**
     * récupère tous les agents
     * @return [type]
     */
    public function recupToutAgent(){
        try{
            $sql = "SELECT * FROM agent";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo "erreur" . $e->getMessage();
        }
    }

    /** 
     * Une fonction qui permet de déconnecter un compte en détruisant sa session précédemment ouverte
     * @return [type]
     */
    public function seDeconnecter(){
        session_start();
        session_unset();
        session_destroy();
    }
    
    /**
     * ajoutConge
     * Ajoute des jours de congé suplémentaire
     * @param  int $id_agent
     * @param  float $jour
     * @return void
     */
    public function ajoutConge($id_agent, $jour){
        $sql = "UPDATE agent SET acquis = acquis + :jour WHERE id_agent = :id_agent";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":jour", $jour);
        $stmt->bindParam(":id_agent", $id_agent);
        return $stmt->execute();
    }

    /**
     * permet de soustraire des jours de congé à un agent
     * @param int $id_agent
     * @param float $jour
     * 
     * @return void
     */
    public function sousConge($id_agent, $jour){
        $sql = "UPDATE agent SET solde = solde - :jour WHERE id_agent = :id_agent";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":jour", $jour);
        $stmt->bindParam(":id_agent", $id_agent);
        return $stmt->execute();
    }

        
    /**
     * ajoutAgent
     * Ajouter un agent à la base de donnée
     * @param  int $id_agent
     * @param  string $nom_agent
     * @param  string $mdp
     * @param  int $statut: 0 pour simple utilisateur et 1 pour Administrateur. Valeur par défaut: 0
     * @param  float $acquis: 0 par défaut
     * @param  float $solde: 0 par défaut
     * @return void
     */
    public function ajoutAgent($id_agent, $mail, $nom_agent, $mdp, $statut = 0, $acquis = 300, $solde = 300){
        $sql = "INSERT INTO agent(id_agent,mail, nom, mdp, statut, acquis, solde) VALUES (:id_agent, :mail, :nom, :mdp, :statut, :acquis, :solde)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id_agent", $id_agent, PDO::PARAM_INT);
        $stmt->bindParam(":nom", $nom_agent, PDO::PARAM_STR);
        $stmt->bindParam(":mail", $mail, PDO::PARAM_STR);
        $stmt->bindParam(":mdp", $mdp, PDO::PARAM_STR);
        $stmt->bindParam(":statut", $statut, PDO::PARAM_BOOL);
        $stmt->bindParam(":acquis", $acquis);
        $stmt->bindParam(":solde", $solde, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function permission(int $statut, int $id_Agent){
        $sql = "UPDATE agent SET statut = :statut WHERE id_agent = :id_Agent ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':statut', $statut, PDO::PARAM_INT);
        $stmt->bindParam(':id_Agent', $id_Agent, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * supprimerAgent
     *suprime un agent de la basede donnée
     * @param  int $id_agent
     * @return void
     */
    public function supprimerAgent($id_agent){
        $sql = "DELETE FROM agent WHERE id_agent = :id_agent";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id_agent", $id_agent);
        return $stmt->execute();
    }
}
