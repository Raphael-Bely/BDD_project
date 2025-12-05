-- Confirm the order by changing the status to “in delivery.”

UPDATE commandes
SET etat = 'en_livraison'
WHERE commande_id = ?