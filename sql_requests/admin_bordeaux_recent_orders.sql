-- Restaurants de Bordeaux, avec le nombre de commandes passées durant les 30 derniers jours

SELECT r.nom,
       COUNT(c.commande_id) AS nb_commandes_30j
FROM restaurants r
LEFT JOIN commandes c ON c.restaurant_id = r.restaurant_id
    AND c.date_commande >= (CURRENT_DATE - INTERVAL '30 days')
WHERE r.adresse ILIKE '%Bordeaux%'
GROUP BY r.nom
ORDER BY nb_commandes_30j DESC;