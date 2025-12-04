-- Get available formules for a restaurant based on current day and time conditions

SELECT 
    f.formule_id, 
    f.nom, 
    f.prix, 
    ci.nom AS nom_categorie
FROM formules AS f
INNER JOIN composer_formules AS cf ON cf.formule_id = f.formule_id
INNER JOIN categories_items AS ci ON ci.categorie_item_id = cf.categorie_item_id
WHERE f.restaurant_id = ?  
  AND (NOT EXISTS (SELECT 1 FROM avoir_conditions_formules WHERE formule_id = f.formule_id)
       OR EXISTS (
           SELECT 1
           FROM avoir_conditions_formules AS acf
           JOIN conditions_formules AS cond ON acf.condition_formule_id = cond.condition_formule_id
           WHERE acf.formule_id = f.formule_id 
             AND cond.jour_disponibilite = EXTRACT(ISODOW FROM CURRENT_DATE)
             AND CURRENT_TIME BETWEEN cond.creneau_horaire_debut AND cond.creneau_horaire_fin
       ))
ORDER BY f.prix