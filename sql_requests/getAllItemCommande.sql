-- Get all items in an order with their quantities

SELECT i.item_id, i.nom, i.prix, c.quantite
FROM items AS i
JOIN contenir_items AS c ON i.item_id = c.item_id
WHERE commande_id = ?