-- Insert a new ingredient
INSERT INTO ingredients (nom, kcal_pour_100g, proteines_pour_100g)
VALUES (?, ?, ?)
RETURNING ingredient_id;
