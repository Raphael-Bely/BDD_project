-- Get the current cart for a client at a specific restaurant

SELECT c.*
FROM commandes AS c
WHERE client_id = ?
  AND restaurant_id = ?
  AND etat = 'en_commande'
ORDER BY c.date_commande DESC
LIMIT 1