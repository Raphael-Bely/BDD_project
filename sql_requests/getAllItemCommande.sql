SELECT i.item_id, i.nom, i.prix, c.quantite
FROM items as i
JOIN contenir_items as c on i.item_id = c.item_id
WHERE commande_id = ?;