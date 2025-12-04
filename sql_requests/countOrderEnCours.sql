-- Count ongoing orders for a client (status 'en_livraison')

SELECT COUNT(*) AS nb 
FROM commandes 
WHERE client_id = ? 
  AND etat = 'en_livraison'