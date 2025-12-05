-- Link (or update) an ingredient to an item with quantity
INSERT INTO composer (item_id, ingredient_id, quantite_g)
VALUES (?, ?, ?)
ON CONFLICT (item_id, ingredient_id)
DO UPDATE SET quantite_g = EXCLUDED.quantite_g;
