SELECT cr.nom AS nom_categorie
FROM categories_restaurants cr
JOIN avoir_categories_restaurants acr ON acr.categorie_restaurant_id = cr.categorie_restaurant_id
WHERE acr.restaurant_id = :id;