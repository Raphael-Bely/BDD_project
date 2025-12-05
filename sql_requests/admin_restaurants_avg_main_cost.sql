-- List of restaurants sorted in descending order by average cost of main course (category_item_id = 2)

SELECT r.nom,
       r.adresse,
       AVG(i.prix) AS cout_moyen
FROM restaurants r
JOIN items i ON i.restaurant_id = r.restaurant_id
WHERE i.categorie_item_id = 2
GROUP BY r.nom, r.adresse
ORDER BY cout_moyen DESC