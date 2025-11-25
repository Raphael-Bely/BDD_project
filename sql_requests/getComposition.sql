SELECT cf.categorie_item_id, ci.nom as nom_categorie 
FROM composer_formules cf
JOIN categories_items ci ON cf.categorie_item_id = ci.categorie_item_id
WHERE cf.formule_id = ? 
ORDER BY cf.categorie_item_id