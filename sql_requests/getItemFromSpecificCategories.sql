-- Get available items from a specific category at a restaurant

SELECT item_id, nom, prix 
FROM items 
WHERE restaurant_id = ? 
  AND categorie_item_id = ? 
  AND est_disponible = TRUE