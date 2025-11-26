SELECT 
    cf.id as instance_id, --id de la formule dans la commande
    f.nom as nom_formule, 
    f.prix, 
    i.nom as nom_item
FROM contenir_formules as cf
INNER JOIN formules as f ON f.formule_id = cf.formule_id
INNER JOIN details_commande_formule as dcf ON dcf.contenir_formule_id = cf.id
INNER JOIN items as i ON i.item_id = dcf.item_id
WHERE cf.commande_id = ?
ORDER BY cf.id;