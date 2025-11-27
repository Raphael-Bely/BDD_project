SELECT c.*, r.nom as nom_restaurant
FROM commandes as c
INNER JOIN restaurants as r ON r.restaurant_id=c.restaurant_id
WHERE client_id = ?
AND etat = 'en_commande'
ORDER BY c.date_commande DESC;