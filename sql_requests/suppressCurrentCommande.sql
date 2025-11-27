DELETE FROM commandes
WHERE commande_id = ?
AND etat = 'en_commande';