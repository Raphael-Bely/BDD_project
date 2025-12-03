-- Clients avec compte (non invités), nombre de commandes et montant total des commandes
-- Hypothèses de schéma basées sur le projet existant:
-- tables: clients(client_id, nom, email, adresse, ...), commandes(commande_id, client_id, date_commande, statut, ...),
-- total calculé via somme des prix des items/plats liés à la commande par une table de détails.
-- Pour éviter la dépendance exacte, on tente d'utiliser getCommandesEnCours / getHistoriqueCommandes patterns

SELECT c.nom,
       c.email,
       COUNT(DISTINCT co.commande_id) AS nb_commandes,
       COALESCE(SUM(co.prix_total_remise), 0) AS montant_total
FROM clients c
LEFT JOIN commandes co ON co.client_id = c.client_id AND co.etat = 'acheve'
WHERE c.email NOT LIKE 'invite_%'
  AND c.email != 'admin@email.fr'
GROUP BY c.nom, c.email
ORDER BY nb_commandes DESC, montant_total DESC;