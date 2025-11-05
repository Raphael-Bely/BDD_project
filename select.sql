--Liste des restaurants de chaque catégorie.

SELECT categorie.*, restaurant.* from categorie as C 
    JOIN lien_categorie_restaurant as L1 on L1.categorie_restaurant_id = C.categorie_restaurant_id  
    JOIN restaurant as R on L1.restaurant_id = R.restaurant_id
    ORDER BY categorie.categorie_restaurant_id;