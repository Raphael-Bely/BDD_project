<?php

require_once 'Query.php';
require_once 'Fidelite.php';

class Commande
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getCurrentCommande($client_id)
    {
        $query = Query::loadQuery('sql_requests/getCurrentCommande.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->execute();
        return $stmt;
    }

    public function suppressCurrentCommande($commande_id)
    {
        $query = Query::loadQuery('sql_requests/suppressCurrentCommande.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->execute();
        return $stmt;
    }

    public function afficherItemCommande($commande_id)
    {
        $query = Query::loadQuery('sql_requests/getAllItemCommande.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->execute();
        return $stmt;
    }

    public function afficherFormulesCommande($commande_id)
    {
        $query = Query::loadQuery('sql_requests/getAllFormulesCommandes.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);
        $stmt->execute();
        return $stmt;
    }

    public function ajouterAuPanier($client_id, $resto_id, $item_id)
    {
        $query = "SELECT ajouter_au_panier(?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $resto_id);
        $stmt->bindParam(3, $item_id);
        return $stmt->execute();
    }

    public function addFullFormuleToOrder($client_id, $resto_id, $formule_id, $items_ids)
    {
        $pg_array = "{" . implode(',', $items_ids) . "}";
        $query = "SELECT ajouter_formule_complete(?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $resto_id);
        $stmt->bindParam(3, $formule_id);
        $stmt->bindParam(4, $pg_array);
        return $stmt->execute();
    }

    //on ajoute les paramètres nécessaires pour la fidélité
    public function confirmOrder($commande_id, $client_id, $restaurant_id, $prix_total)
    {
        //on exécute la requête SQL qui valide la commande (UPDATE est_acheve = true...)
        $query = Query::loadQuery('sql_requests/confirmCommande.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id);

        if ($stmt->execute()) {
            //si la commande est validée en BDD, on ajoute les points
            //on instancie le modèle Fidelite en lui passant la connexion BDD actuelle
            $fidelite = new Fidelite($this->conn);

            //on appelle la méthode qui gère l'ajout ou la création du compte fidélité
            $fidelite->ajouterPoints($client_id, $restaurant_id, $prix_total);

            return true;
        }

        return false;
    }

    public function getHistoriqueCommandes($client_id)
    {
        $query = Query::loadQuery('sql_requests/getHistoriqueCommandes.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->execute();
        return $stmt;
    }

    public function getCommandesEnCours($client_id)
    {
        $query = Query::loadQuery('sql_requests/getCommandesEnCours.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':client_id', $client_id);
        $stmt->execute();

        return $stmt;
    }

    public function marquerCommeRecue($commande_id)
    {
        $query = "UPDATE commandes SET etat = 'acheve' WHERE commande_id = :commande_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':commande_id', $commande_id);

        return $stmt->execute();
    }
} ?>