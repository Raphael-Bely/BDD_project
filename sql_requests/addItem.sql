-- Adds an item to a restaurant's menu, linking it to its category
-- Returns the item ID so it can be retrieved if linked to another item (see restaurateur_space accompaniment) 

INSERT INTO items (nom, prix, est_disponible, restaurant_id, categorie_item_id) 
VALUES (?, ?, ?, ?, ?)
RETURNING item_id;