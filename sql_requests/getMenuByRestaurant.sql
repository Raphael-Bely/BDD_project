SELECT i.item_id, i.nom, i.prix, c.nom as nom_categorie, p.nom as nom_propriete
FROM items as i
INNER JOIN categories_items as c ON c.categorie_item_id=i.categorie_item_id
LEFT JOIN avoir_proprietes_items as api ON api.item_id=i.item_id
LEFT JOIN proprietes_items as p ON p.propriete_items_id=api.propriete_items_id 
WHERE restaurant_id = ?
ORDER BY i.categorie_item_id;