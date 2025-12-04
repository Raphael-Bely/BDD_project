-- Delete a cart (order in progress) before confirmation

DELETE FROM commandes
WHERE commande_id = ?
  AND etat = 'en_commande'