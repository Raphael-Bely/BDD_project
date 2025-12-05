-- Retrieves orders with the status “in delivery” for the tracking page

SELECT COUNT(*) AS nb 
FROM commandes 
WHERE client_id = ? 
  AND etat = 'en_livraison'