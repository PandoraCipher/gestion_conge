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
     * @var string
     */
    public $nom;

    /**
     * 
     * @var int
     */
    public $statut;

    /**
     * @var float
     */
    public $acquis;

    /**
     * @var float
     */
    public $solde;


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
            $sql = "SELECT * FROM agent WHERE nom = :nom";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user){
                if((password_verify($mdp, $user['mdp']))){
                $this->id_agent = $user['id_agent'];
                $this->nom = $user['nom'];
                $this->statut = $user['statut'];
                $this->acquis = $user['acquis'];
                $this->solde = $user['solde'];
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
    public function ajoutAgent($id_agent, $nom_agent, $mdp, $statut = 0, $acquis = 0, $solde = 0){
        $sql = "INSERT INTO agent(id_agent, nom, mdp, statut, acquis, solde) VALUES (:id_agent, :nom, :mdp, :statut, :acquis, :solde)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id_agent", $id_agent, PDO::PARAM_INT);
        $stmt->bindParam(":nom", $nom_agent, PDO::PARAM_STR);
        $stmt->bindParam(":mdp", $mdp, PDO::PARAM_STR);
        $stmt->bindParam(":statut", $statut, PDO::PARAM_BOOL);
        $stmt->bindParam(":acquis", $acquis);
        $stmt->bindParam(":solde", $solde, PDO::PARAM_INT);
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
