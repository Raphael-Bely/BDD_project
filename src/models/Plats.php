<?php

require_once 'Query.php';

class Plat
{
    private $conn;

    // Database connection initialization.
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get full menu for a restaurant.
    public function getMenuByRestaurant($restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getMenuByRestaurant.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $restaurant_id);
        $stmt->execute();

        return $stmt;
    }

    // Get available items for a specific category and restaurant.
    public function getItemsDisponibles($restaurant_id, $categorie_id)
    {
        $query = Query::loadQuery('sql_requests/getItemFromSpecificCategories.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$restaurant_id, $categorie_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new item to the database.
    public function addItem($nom, $prix, $est_disponible, $restaurant_id, $categorie_item_id)
    {
        $query = Query::loadQuery('sql_requests/addItem.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $prix);
        $stmt->bindParam(3, $est_disponible);
        $stmt->bindParam(4, $restaurant_id);
        $stmt->bindParam(5, $categorie_item_id);

        $stmt->execute();

        return $stmt->fetchColumn();


    }

    // Get all item categories.
    public function getItemFromAllCat()
    {
        $query = Query::loadQuery('sql_requests/getItemFromAllCategories.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Search for items by name within a restaurant.
    public function searchItem($restaurant_id, $term)
    {
        $searchTerm = "%" . $term . "%";

        $sql = Query::loadQuery("sql_requests/searchItem.sql");

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([$restaurant_id, $searchTerm]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function getItemsForOwner($restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getItemsByRestaurantOwner.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$restaurant_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateItem($item_id, $restaurant_id, $nom, $prix, $est_disponible, $categorie_item_id)
    {
        $query = Query::loadQuery('sql_requests/updateItem.sql');
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nom, $prix, $est_disponible, $categorie_item_id, $item_id, $restaurant_id]);
    }

    public function deleteItem($item_id, $restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/deleteItem.sql');
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$item_id, $restaurant_id]);
    }

    public function toggleItemAvailability($item_id, $restaurant_id)
    {
        $sql = "UPDATE items SET est_disponible = NOT est_disponible 
                WHERE item_id = ? AND restaurant_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$item_id, $restaurant_id]);
    }

}

?>