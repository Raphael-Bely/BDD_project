SELECT c.*
FROM contenir_items as c
WHERE commande_id = ?
AND item_id = ?;