SELECT c.*, r.nom as nom_restaurant
FROM commandes as c
INNER JOIN restaurants as r ON r.restaurant_id=c.restaurant_id
WHERE client_id = ?
AND est_acheve = FALSE
ORDER BY c.date_commande DESC;