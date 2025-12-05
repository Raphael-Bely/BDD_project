<?php
require_once 'config/Database.php';
require_once __DIR__ . '/Query.php';

class Restaurant
{
    private $conn;
    
    // Database connection initialization.
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all currently open restaurants.
    public function getAllOpenedRestaurants()
    {

        $query = Query::loadQuery('sql_requests/getAllOpenedRestaurants.sql');
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    // Get all currently closed restaurants.
    public function getAllClosedRestaurants()
    {

        $query = Query::loadQuery('sql_requests/getAllClosedRestaurants.sql');
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    // Get restaurant details by ID.
    public function getByID($restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getRestaurantById.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $restaurant_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    // Get all formulas for a restaurant.
    public function getFormules($restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getFormulasByRestaurantId.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $restaurant_id);
        $stmt->execute();

        return $stmt;
    }

    // Get current active order for a client in a restaurant.
    public function getCurrentCommandFromRestaurant($client_id, $restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getCurrentOrderFromRestaurant.sql');

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $restaurant_id);

        $stmt->execute();

        return $stmt;
    }

    // Get all restaurant categories.
    public function getAllCategories()
    {
        $query = Query::loadQuery('sql_requests/getAllCategories.sql');
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        return $stmt;
    }

    // Get restaurant category details by ID.
    public function getCategoriesById($cat_id)
    {
        $query = Query::loadQuery('sql_requests/getCategorieById.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $cat_id);

        $stmt->execute();
        return $stmt;
    }
    
    // Get open restaurants filtered by category.
    public function getOpenedByCategory($category_id) {
        $query = Query::loadQuery('sql_requests/getOpenedByCategory.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category_id);
        $stmt->execute();
        return $stmt;
    }

    // Get closed restaurants filtered by category.
    public function getClosedByCategory($category_id) {
        $query = Query::loadQuery('sql_requests/getClosedByCategory.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category_id);
        $stmt->execute();
        return $stmt;
    }

    // Get current order for client/restaurant pair.
    public function getCurrentCommandeFromRestaurant($client_id, $restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getCurrentOrderFromRestaurant.sql');
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $client_id);
        $stmt->bindParam(2, $restaurant_id);

        $stmt->execute();
        return $stmt;
    }

    // Find restaurants within a specific radius.
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

    // Authenticate restaurant owner.
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

    // Get sales statistics for a restaurant.
    public function getStats($restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getRestaurantStats.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $restaurant_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Remove an item from the current order.
    public function suppressItemCommande($commande_id, $item_id)
    {
        $query = "SELECT supprimer_au_panier(?::INTEGER, ?::INTEGER)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $commande_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $item_id, PDO::PARAM_INT);
        $stmt->execute();
        return;
    }

    // Get restaurant opening hours.
    public function getHoraires($restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getOpeningHours.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$restaurant_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new opening hours slot.
    public function addHoraire($restaurant_id, $jour, $debut, $fin)
    {
        try {
            $this->conn->beginTransaction();

            // 1. Create schedule slot.
            $sql1 = Query::loadQuery('sql_requests/createOpeningHours.sql');
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->execute([$jour, $debut, $fin]);
            $horaire_id = $stmt1->fetchColumn();

            // 2. Link slot to restaurant.
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

    // Delete an opening hours slot link.
    public function deleteHoraire($restaurant_id, $horaire_id)
    {
        $sql = Query::loadQuery('sql_requests/deleteRestaurantScheduleLink.sql');
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$restaurant_id, $horaire_id]);
    }
}