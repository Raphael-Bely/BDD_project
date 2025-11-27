UPDATE commandes
SET prix_total_remise = (
    SELECT COALESCE(SUM(i.prix * ci.quantite), 0)
    FROM contenir_items ci
    JOIN items i ON ci.item_id = i.item_id
    WHERE ci.commande_id = commandes.commande_id
)
WHERE commande_id = ?;