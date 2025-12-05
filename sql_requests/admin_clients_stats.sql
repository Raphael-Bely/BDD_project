-- List of customers with accounts (excluding guests and administrators) with the number of orders and the total amount
SELECT c.nom,
       c.email,
       COUNT(DISTINCT co.commande_id) AS nb_commandes,
       COALESCE(SUM(co.prix_total_remise), 0) AS montant_total
FROM clients c
LEFT JOIN commandes co ON co.client_id = c.client_id AND co.etat = 'acheve'
WHERE c.email NOT LIKE 'invite_%'
  AND c.email != 'admin@email.fr'
GROUP BY c.nom, c.email
ORDER BY nb_commandes DESC, montant_total DESC