UPDATE contenir_items
SET quantite = quantite + 1
WHERE commande_id = ?
AND item_id = ?;
