SELECT r.* FROM restaurants r
JOIN avoir_categories_restaurants acr ON r.restaurant_id = acr.restaurant_id
WHERE acr.categorie_restaurant_id = ?;