WITH All_item_sales AS (

    SELECT commande_id, item_id, 'Plat' AS type_vente
    FROM contenir_items

    UNION ALL 

    SELECT cf.commande_id, dcf.item_id, 'Menu' AS type_vente
    FROM contenir_formules cf
    JOIN details_commande_formule dcf ON cf.id = dcf.contenir_formule_id
)

SELECT 
    EXTRACT(YEAR FROM C.date_commande) AS annee, 
    EXTRACT(MONTH FROM C.date_commande) AS mois, 
    I.nom,
    COUNT(*) as nb_total_ventes, 
    SUM(CASE WHEN S.type_vente = 'Plat' THEN 1 ELSE 0 END) AS dont_x_plat,
    SUM(CASE WHEN S.type_vente = 'Menu' THEN 1 ELSE 0 END) AS dont_x_menu,
    (COUNT(*) * I.prix) AS revenu_theorique 

FROM All_item_sales AS S
JOIN commandes AS C ON S.commande_id = C.commande_id
JOIN items AS I ON S.item_id = I.item_id

WHERE C.date_commande > (NOW() - INTERVAL '1 year')
AND I.restaurant_id = ? 

GROUP BY I.item_id, I.nom, annee, mois
ORDER BY I.item_id, annee DESC, mois DESC, nb_total_ventes DESC;