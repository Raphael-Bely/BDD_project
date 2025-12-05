<?php
require_once __DIR__ . '/Query.php';

class Formule
{
    private $conn;

    // Database connection initialization.
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get items composing a specific formula.
    public function getComposition($formule_id)
    {
        $query = Query::loadQuery('sql_requests/getComposition.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$formule_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new formula with items and availability conditions.
    public function createFormule($nom, $prix, $resto_id, $categories_ids, $conditions_data)
    {

        // Atomic transaction to ensure data integrity.
        try {
            // Start transaction.
            $this->conn->beginTransaction();

            $query = Query::loadQuery("sql_requests/createFormula.sql");
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$nom, $prix, $resto_id]);
            $formule_id = $stmt->fetchColumn();

            if (!$formule_id)
                throw new Exception("Erreur création formule");

            $sql_comp = Query::loadQuery('sql_requests/linkFormulaAndItemCategory.sql');
            $stmt_comp = $this->conn->prepare($sql_comp);

            foreach ($categories_ids as $cat_id) {
                $stmt_comp->execute([$formule_id, $cat_id]);
            }

            if (!empty($conditions_data)) {

                // Check if condition exists.
                $sql_check = Query::loadQuery("sql_requests/checkConditions.sql");
                $stmt_check = $this->conn->prepare($sql_check);

                // Create condition if not exists.
                $sql_create_cond = Query::loadQuery("sql_requests/createConditions.sql");
                $stmt_create_cond = $this->conn->prepare($sql_create_cond);

                // Link condition to formula.
                $sql_link = Query::loadQuery("sql_requests/linkConditionToFormule.sql");
                $stmt_link = $this->conn->prepare($sql_link);

                foreach ($conditions_data as $cond) {
                    $jour = $cond['jour'];
                    $debut = $cond['debut'];
                    $fin = $cond['fin'];

                    // Check existence.
                    $stmt_check->execute([$jour, $debut, $fin]);
                    $cond_id = $stmt_check->fetchColumn();

                    // Create if missing.
                    if (!$cond_id) {
                        $stmt_create_cond->execute([$jour, $debut, $fin]);
                        $cond_id = $stmt_create_cond->fetchColumn();
                    }

                    // Link condition.
                    $stmt_link->execute([$formule_id, $cond_id]);
                }
            }

            // Commit transaction.
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Rollback transaction on error.
            $this->conn->rollBack();
            return false;
        }
    }

    // Retrieve all available formula conditions.
    public function getAllConditions()
    {
        $sql = "SELECT condition_formule_id, jour_disponibilite, creneau_horaire_debut, creneau_horaire_fin 
                FROM conditions_formules 
                ORDER BY jour_disponibilite, creneau_horaire_debut";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFormulesForOwner($restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/getFormulesByRestaurantOwner.sql');
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$restaurant_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateFormule($formule_id, $restaurant_id, $nom, $prix)
    {
        $query = Query::loadQuery('sql_requests/updateFormule.sql');
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nom, $prix, $formule_id, $restaurant_id]);
    }

    public function deleteFormule($formule_id, $restaurant_id)
    {
        $query = Query::loadQuery('sql_requests/deleteFormule.sql');
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$formule_id, $formule_id, $formule_id, $restaurant_id]);
    }
}
?>