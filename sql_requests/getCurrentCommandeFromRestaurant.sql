SELECT c.*
FROM commandes as c
WHERE client_id = ?
AND restaurant_id = ?
AND etat = 'en_commande'
ORDER BY c.date_commande DESC
LIMIT 1;