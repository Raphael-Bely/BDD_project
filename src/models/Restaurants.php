<?php
require_once 'config/Database.php';
require_once __DIR__ . '/Query.php';

class Restaurant
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllOpenedRestaurants()
    {

        $query = Query::loadQuery('sql_requests/getAllOpenedRestaurants.sql');
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function getAllClosedRestaurants()
    {

        $query = Query::loadQuery('sql_requests/getAllClosedRestaurants.sql');
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function getByID($restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getRestaurantById.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $restaurant_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public function getFormules($restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getFormulasByRestaurantId.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $restaurant_id);
        $stmt->execute();

        return $stmt;
    }

    public function getCurrentCommandFromRestaurant($client_id, $restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getCurrentOrderFromRestaurant.sql');

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $restaurant_id);

        $stmt->execute();

        return $stmt;
    }

    public function getAllCategories()
    {
        $query = Query::loadQuery('sql_requests/getAllCategories.sql');
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

    public function getCategoriesById($cat_id)
    {
        $query = Query::loadQuery('sql_requests/getCategorieById.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $cat_id);

        $stmt->execute();
        return $stmt;
    }
    public function getOpenedByCategory($category_id) {
        $query = Query::loadQuery('sql_requests/getOpenedByCategory.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category_id);
        $stmt->execute();
        return $stmt;
    }

    public function getClosedByCategory($category_id) {
        $query = Query::loadQuery('sql_requests/getClosedByCategory.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category_id);
        $stmt->execute();
        return $stmt;
    }

    public function getCurrentCommandeFromRestaurant($client_id, $restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getCurrentOrderFromRestaurant.sql');
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $restaurant_id);

        $stmt->execute();
        return $stmt;
    }

    public function getRestaurantsAround($lat, $lon, $rayon_km)
    {
        $query = Query::loadQuery('sql_requests/getRestaurantAround.sql');
        $stmt = $this->conn->prepare($query);

        $rayon_m = $rayon_km * 1000;

        $stmt->bindParam(1, $lon);
        $stmt->bindParam(2, $lat);
        $stmt->bindParam(3, $lon);
        $stmt->bindParam(4, $lat);
        $stmt->bindParam(5, $rayon_m);

        $stmt->execute();
        return $stmt;
    }

    public function login($email, $mot_de_passe)
    {
        $query = Query::loadQuery('sql_requests/restaurantLogin.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $resto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resto && $mot_de_passe === $resto['mot_de_passe']) {
            return $resto;
        }
        return false;
    }

    public function getStats($restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getRestaurantStats.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $restaurant_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function suppressItemCommande($commande_id, $item_id)
    {
        $query = "SELECT supprimer_au_panier(?::INTEGER, ?::INTEGER)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $item_id, PDO::PARAM_INT);
        $stmt->execute();
        return;
    }

    public function getHoraires($restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getOpeningHours.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$restaurant_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addHoraire($restaurant_id, $jour, $debut, $fin)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Créer le créneau horaire
            $sql1 = Query::loadQuery('sql_requests/createOpeningHours.sql');
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute([$jour, $debut, $fin]);
            $horaire_id = $stmt1->fetchColumn();

            // 2. Lier ce créneau au restaurant
            $sql2 = Query::loadQuery('sql_requests/linkScheduleToRestaurant.sql');
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->execute([$restaurant_id, $horaire_id]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function deleteHoraire($restaurant_id, $horaire_id)
    {
        $sql = Query::loadQuery('sql_requests/deleteRestaurantScheduleLink.sql');
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$restaurant_id, $horaire_id]);
    }
}
?>