<?php

class Query {

    // Load SQL query content from file.
    static public function loadQuery($query_name) {
        $path = __DIR__ . '/../../'. $query_name;
        $query = file_get_contents($path);

        if ($query == false) {
            die("Erreur : Impossible de lire le fichier SQL : ". $path);
        }

        return $query;
    } 
}

?>