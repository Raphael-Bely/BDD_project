-- Creates a new formula instance for a restaurant, returns the formula_id to verify the
-- success of the creation in order to continue the atomic transaction 

INSERT INTO formules (nom, prix, restaurant_id) 
VALUES (?, ?, ?) 
RETURNING formule_id