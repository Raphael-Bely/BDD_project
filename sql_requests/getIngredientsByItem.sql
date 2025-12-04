-- Get all ingredients for a specific item with quantities

SELECT i.*, c.quantite_g
FROM ingredients AS i
INNER JOIN composer AS c ON c.ingredient_id = i.ingredient_id
INNER JOIN items AS t ON t.item_id = c.item_id
WHERE t.item_id = ?
ORDER BY c.quantite_g DESC