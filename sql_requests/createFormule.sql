-- Create a new formule (meal deal) for a restaurant

INSERT INTO formules (nom, prix, restaurant_id) 
VALUES (?, ?, ?) 
RETURNING formule_id