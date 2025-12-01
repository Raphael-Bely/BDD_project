SELECT c.*, r.nom as restaurant_nom, r.adresse as restaurant_adresse 
                FROM commandes c 
                JOIN restaurants r ON c.restaurant_id = r.restaurant_id 
                WHERE c.commande_id = ? AND c.client_id = ?"