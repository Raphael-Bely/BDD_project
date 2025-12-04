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

    public function createFormule($nom, $prix, $resto_id, $categories_ids)
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

            // Si arrivé la alors écrire "au propre"
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Si problème annuler le tout
            $this->conn->rollBack();
            return false;
        }
    }
}
?>