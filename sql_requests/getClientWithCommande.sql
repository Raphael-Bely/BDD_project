-- Get the client ID associated with a specific order

SELECT client_id 
FROM commandes 
WHERE commande_id = ?