-- List restaurants ranked by descending average cost of main dishes (categorie_item_id = 2)

SELECT r.nom,
       r.adresse,
       AVG(i.prix) AS cout_moyen
FROM restaurants r
JOIN items i ON i.restaurant_id = r.restaurant_id
WHERE i.categorie_item_id = 2
GROUP BY r.nom, r.adresse
ORDER BY cout_moyen DESC