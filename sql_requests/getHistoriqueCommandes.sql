-- Get order history for a client (completed orders only)

SELECT 
    c.commande_id,
    c.date_commande,
    c.heure_retrait,
    c.prix_total_remise AS prix_total,
    c.etat,
    r.nom AS restaurant_nom,
    r.adresse AS restaurant_adresse
FROM commandes c
JOIN restaurants r ON c.restaurant_id = r.restaurant_id
WHERE c.client_id = :client_id
  AND c.etat = 'acheve'
ORDER BY c.date_commande DESC
