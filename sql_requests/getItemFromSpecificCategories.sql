SELECT item_id, nom, prix FROM items 
WHERE restaurant_id = ? 
AND categorie_item_id = ? 
AND est_disponible = TRUE