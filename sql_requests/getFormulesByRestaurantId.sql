SELECT f.nom, f.prix,ci.nom as nom_categorie
FROM formules as f
INNER JOIN composer_formules as cf on cf.formule_id=f.formule_id
INNER JOIN categories_items as ci on ci.categorie_item_id=cf.categorie_item_id
WHERE f.restaurant_id = ?
