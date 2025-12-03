-- Restaurants classés par ordre décroissant du coût moyen des plats principaux
-- categorie_item_id = 2 correspond à 'Principal'

SELECT r.nom,
       r.adresse,
       AVG(i.prix) AS cout_moyen
FROM restaurants r
JOIN items i ON i.restaurant_id = r.restaurant_id
WHERE i.categorie_item_id = 2
GROUP BY r.nom, r.adresse
ORDER BY cout_moyen DESC;