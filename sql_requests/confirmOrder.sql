-- Confirm an order by changing its status to 'en_livraison'

UPDATE commandes
SET etat = 'en_livraison'
WHERE commande_id = ?