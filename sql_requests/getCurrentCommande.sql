-- Get the current cart (order in progress) for a client

SELECT c.*, r.nom AS nom_restaurant
FROM commandes AS c
INNER JOIN restaurants AS r ON r.restaurant_id = c.restaurant_id
WHERE client_id = ?
  AND etat = 'en_commande'
ORDER BY c.date_commande DESC