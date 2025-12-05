-- Remove a specific ingredient from an item
DELETE FROM composer
WHERE item_id = ? AND ingredient_id = ?;
