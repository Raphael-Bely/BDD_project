<?php


require_once 'Query.php';

class Ingredient
{
    private $conn;

    // Database connection initialization.
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get list of ingredients for a specific item.
    public function getIngredientsByItem($item_id)
    {
        $query = Query::loadQuery('sql_requests/getIngredientsByItem.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $item_id);
        $stmt->execute();

        return $stmt;
    }

    public function createIngredient($nom, $kcal_100g, $proteines_100g)
    {
        $query = Query::loadQuery('sql_requests/addIngredient.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$nom, $kcal_100g, $proteines_100g]);
        return (int) $stmt->fetchColumn();
    }

    public function linkIngredientToItem($item_id, $ingredient_id, $quantite_g)
    {
        $query = Query::loadQuery('sql_requests/linkIngredientToItem.sql');
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$item_id, $ingredient_id, $quantite_g]);
    }

    public function deleteIngredientFromItem($item_id, $ingredient_id)
    {
        $query = Query::loadQuery('sql_requests/deleteIngredientFromItem.sql');
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$item_id, $ingredient_id]);
    }
}

?>