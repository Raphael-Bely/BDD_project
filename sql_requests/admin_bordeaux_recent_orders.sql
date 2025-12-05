-- List of restaurants in Bordeaux with their number of orders in the last month
-- CURRENT_DATE: PostgreSQL function that returns today's date

SELECT r.nom, 
       COUNT(c.commande_id) AS nb_commandes_30j
FROM restaurants r
LEFT JOIN commandes c ON c.restaurant_id = r.restaurant_id
    AND c.date_commande >= (CURRENT_DATE - INTERVAL '30 days')
WHERE r.adresse ILIKE '%Bordeaux%'
GROUP BY r.nom
ORDER BY nb_commandes_30j DESC