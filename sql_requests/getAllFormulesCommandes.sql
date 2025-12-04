-- Get all formules (meal deals) in an order with their selected items

SELECT 
    cf.id AS instance_id,
    f.nom AS nom_formule, 
    f.prix, 
    i.nom AS nom_item
FROM contenir_formules AS cf
INNER JOIN formules AS f ON f.formule_id = cf.formule_id
INNER JOIN details_commande_formule AS dcf ON dcf.contenir_formule_id = cf.id
INNER JOIN items AS i ON i.item_id = dcf.item_id
WHERE cf.commande_id = ?
ORDER BY cf.id