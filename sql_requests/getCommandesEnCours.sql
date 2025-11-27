-- Requête pour obtenir les commandes en cours (confirmées mais pas encore reçues)
SELECT 
    c.commande_id,
    c.date_commande,
    c.heure_retrait,
    c.prix_total_remise as prix_total,
    c.etat,
    r.nom as restaurant_nom,
    r.adresse as restaurant_adresse
FROM commandes c
JOIN restaurants r ON c.restaurant_id = r.restaurant_id
WHERE c.client_id = :client_id
    AND c.etat = 'en_livraison'
ORDER BY c.date_commande DESC;
