-- Get detailed information about a specific order for a client

SELECT c.*, r.nom AS restaurant_nom, r.adresse AS restaurant_adresse 
FROM commandes c 
JOIN restaurants r ON c.restaurant_id = r.restaurant_id 
WHERE c.commande_id = ? 
  AND c.client_id = ?