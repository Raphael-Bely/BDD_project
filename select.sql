-- ============================================================================
-- CONFIGURATION: Valeurs par défaut pour les requètes paramétrées
-- ============================================================================
\set restaurant_id 1
\set user_longitude -0.5596
\set user_latitude 44.8378
\set months_back 12

-- ============================================================================
-- REQUETE CONSULTATION
-- ============================================================================

--Liste des restaurants de chaque catégorie.

SELECT CR.nom as catégorie, R.nom as restaurant, R.adresse, R.coordonnees_gps from categories_restaurants as CR
    JOIN avoir_categories_restaurants as L1 on L1.categorie_restaurant_id = CR.categorie_restaurant_id  
    JOIN restaurants as R on L1.restaurant_id = R.restaurant_id
    ORDER BY CR.categorie_restaurant_id;


--Liste des restaurants selon la disponibilité de plats de chaque catégorie.

SELECT R.nom AS nom_restaurant, CI.nom AS nom_categorie, COUNT(I.item_id) AS nombre_de_plats_disponibles
FROM restaurants AS R
CROSS JOIN categories_items AS CI --équivalent produit restaurants-catégories_items
LEFT JOIN items AS I ON R.restaurant_id = I.restaurant_id
AND CI.categorie_item_id = I.categorie_item_id
AND I.est_disponible = TRUE
GROUP BY
    R.restaurant_id, R.nom, CI.categorie_item_id, CI.nom
ORDER BY
    nom_restaurant,
    nom_categorie;



--La liste des commandes passées par des clients sans compte.

SELECT Co.*, Cl.client_id from clients as Cl 
    JOIN commandes as Co on Co.client_id = Cl.client_id
    WHERE Cl.client_id NOT IN (

            SELECT F.client_id from fidelite as F
    );


--REQUETE STATISTIQUE 

--La liste des clients avec un compte, avec le nombre de commandes qu’ils ont passé, et le montant total.

SELECT Cl.client_id, Cl.nom, count(Co.commande_id), SUM(Co.prix_total_remise) as montant_total from clients as Cl 
    JOIN fidelite as F on F.client_id = Cl.client_id
    JOIN commandes as Co on Co.client_id = Cl.client_id
    GROUP BY Cl.client_id, Cl.nom;


--La liste des restaurants classés par ordre décroissant du coût moyen des plats principaux.

SELECT R.nom, R.restaurant_id, AVG(I.prix) as moyenne_prix_plat_principale from restaurants  as R
    JOIN items as I on I.restaurant_id = R.restaurant_id
    JOIN categories_items as CI on CI.categorie_item_id = I.categorie_item_id
    WHERE CI.nom='Principal'
    GROUP BY R.nom, R.restaurant_id
    ORDER BY moyenne_prix_plat_principale DESC;



--La liste des restaurant de Bordeaux, avec le nombre de commandes passées durant les 30 derniers jours.

SELECT R.restaurant_id, R.nom, COUNT(Co.commande_id) AS nb_commandes 
    FROM  restaurants AS R
    JOIN commandes AS Co ON R.restaurant_id = Co.restaurant_id
    WHERE UPPER(R.adresse) LIKE UPPER('%Bordeaux%')
        AND Co.date_commande >= (NOW() - INTERVAL '30 days')
    GROUP BY R.restaurant_id, R.nom;


--Chaque restaurant doit pouvoir consulter les statistiques de commandes de chaque plat par mois, pour l’année écoulée.

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

WHERE C.date_commande >= (NOW() - (:months_back || ' months')::interval)
AND I.restaurant_id = :restaurant_id 

GROUP BY I.item_id, I.nom, annee, mois
ORDER BY annee DESC, mois DESC, nb_total_ventes DESC;


-- Un utilisateur qui renseigne sa position (GPS) peut consulter la liste des restaurtants disponibles, dans un rayon de 2km, rangés par ordre croissant de distance.

SELECT R.nom, R.adresse, ST_Distance(R.coordonnees_gps, ST_SetSRID(ST_MakePoint(:user_longitude, :user_latitude), 4326)) as distance_en_m --longitude, latitude
FROM restaurants as R
WHERE ST_DWithin(
    R.coordonnees_gps,
    ST_SetSRID(ST_MakePoint(:user_longitude, :user_latitude), 4326), --longitude, latitude
    2000
)
ORDER BY distance_en_m ASC;