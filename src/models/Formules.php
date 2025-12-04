<?php
require_once __DIR__ . '/Query.php';

class Formule
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getComposition($formule_id)
    {
        $query = Query::loadQuery('sql_requests/getComposition.sql');

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$formule_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createFormule($nom, $prix, $resto_id, $categories_ids, $conditions_data)
    {

        // Transaction atomique, soit tout ce passe bien soit rien ne se passe
        try {
            // Ecriture "au brouillon"
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
                
                // Requête pour vérifier l'existence
                $sql_check = Query::loadQuery("sql_requests/checkConditions.sql");
                $stmt_check = $this->conn->prepare($sql_check);

                // Requête pour créer si inexistant
                $sql_create_cond = Query::loadQuery("sql_requests/createConditions.sql");
                $stmt_create_cond = $this->conn->prepare($sql_create_cond);

                // Requête pour lier à la formule
                $sql_link = "INSERT INTO avoir_conditions_formules (formule_id, condition_formule_id) VALUES (?, ?)";
                $stmt_link = $this->conn->prepare($sql_link);

                foreach ($conditions_data as $cond) {
                    $jour = $cond['jour'];
                    $debut = $cond['debut'];
                    $fin = $cond['fin'];

                    // A. On vérifie si ce créneau existe déjà
                    $stmt_check->execute([$jour, $debut, $fin]);
                    $cond_id = $stmt_check->fetchColumn();

                    // B. S'il n'existe pas, on le crée
                    if (!$cond_id) {
                        $stmt_create_cond->execute([$jour, $debut, $fin]);
                        $cond_id = $stmt_create_cond->fetchColumn();
                    }

                    // C. On lie la condition (existante ou nouvelle) à la formule
                    $stmt_link->execute([$formule_id, $cond_id]);
                }
            }
                
            // Si arrivé la alors écrire "au propre"
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
                // Si problème annuler le tout
                $this->conn->rollBack();
                return false;
        }
    }

    public function getAllConditions() {
        // On récupère les conditions pour les afficher (ex: Jour 1, 12:00 - 15:00)
        $sql = "SELECT condition_formule_id, jour_disponibilite, creneau_horaire_debut, creneau_horaire_fin 
                FROM conditions_formules 
                ORDER BY jour_disponibilite, creneau_horaire_debut";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>