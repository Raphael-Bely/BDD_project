--REQUETE CONSULTATION

--Liste des restaurants de chaque catégorie.

SELECT CR.*, R.* from catégories_restaurants as CR
    JOIN avoir_catégories_restaurants as L1 on L1.categorie_restaurant_id = CR.categorie_restaurant_id  
    JOIN restaurants as R on L1.restaurant_id = R.restaurant_id
    ORDER BY CR.categorie_restaurant_id;


--Liste des restaurants selon la disponibilité de plats de chaque catégorie.

SELECT CI.*,I.* from categories_items as CI
    JOIN specifier as S on S.categorie_id = CI.categorie_id
    JOIN Items as I on I.items_id = S.items_id
    JOIN Restaurant as R on R.restaurant_id = I.restaurant_id
    WHERE I.disponibilite = TRUE;


--La liste des commandes passées par des clients sans compte.

SELECT C.* from clients as Cl 
    JOIN commandes as Co on Co.client_id = Cl.client_id
    WHERE Cl.client_id NOT IN (

            SELECT F.client_id from fidelité as F
    );


--REQUETE STATISTIQUE 

--La liste des clients avec un compte, avec le nombre de commandes qu’ils ont passé, et le montant total.

SELECT Cl.client_id, Cl.nom, count(Co.commande_id), SUM(Co.prix_total_remisé) from clients as Cl 
    JOIN fidelité as F on F.client_id = Cl.client_id
    JOIN commandes as Co on Co.client_id = Cl.client_id
    GROUP BY Cl.client_id, Cl.nom;


--La liste des restaurants classés par ordre décroissant du coût moyen des plats principaux.

SELECT R.nom, R.restaurant_id, AVG(I.prix_item) as moyenne_prix_plat_principale from restaurants
    JOIN items as I on I.restaurant_id = R.restaurant_id
    JOIN categories_items as CI on CI.categorie_item_id = I.categorie_item_id
    WHERE CI.nom = "Principal"
    GROUP BY R.nom, R.restaurant_id
    ORDER BY DESC moyenne_prix_plat_principale;



--La liste des restaurant de Bordeaux, avec le nombre de commandes passées durant les 30 derniers jours.

SELECT R.restaurant_id, R.nom, COUNT(Co.commande_id) AS nb_commandes 
    FROM  restaurants AS R
    JOIN commandes AS Co ON R.restaurant_id = Co.restaurant_id
    WHERE UPPER(R.adresse) LIKE UPPER('%Bordeaux%')
        AND Co.date_commande >= (NOW() - INTERVAL '30 days')
    GROUP BY R.restaurant_id, R.nom;


--Chaque restaurant doit pouvoir consulter les statistiques de commandes de chaque plat par mois, pour l’année écoulée.

--On commence par superposer toutes les ventes/commandes des items en traitant leur provenance avec l'ajout d'un champ type_vente (nécessaire pour compter tout les items commandé)
WITH All_item_sales AS (
    SELECT commande_id, items_id, 'Plat' AS type_vente
    FROM contenir_items

    UNION ALL 

    SELECT commande_id, items_id, 'Menu' AS type_vente
    FROM contenir_formules
)

--On sélectionne l'annee, le mois, le nombre de vente total, combien proviennent de Plat et combien proviennent de Menu et le revenu total du plat
SELECT YEAR(C.date_commande) AS annee, MONTH(C.date_commande) AS mois, I.nom,
    COUNT(*) as nb_total_ventes, SUM(CASE WHEN S.type_vente = 'Plat' THEN 1 ELSE 0 END) AS dont_x_plat,
    SUM(CASE WHEN S.type_vente = 'Menu' THEN 1 ELSE 0 END) AS dont_x_menu,
    (COUNT(*) * I.prix) AS revenu

--On regroupe nos ventes totales avec la table commande pour récuperer la date de commande et avec la table items pour récupérer nom, prix et restaurant
FROM All_item_sales AS S
JOIN commandes AS C ON V.commande_id = C.commande_id
JOIN items AS I ON V.item_id = I.item_id

-- On filtre pour ne récupérer que les ventes/commandes de l'année passée et du restaurant sélectionné
WHERE C.date_commande > DATE_SUB(NOW(), INTERVAL 1 YEAR)
AND I.restaurant_id = 123 --À remplacer

-- On regroupe par plat, année et mois comme demandé dans la question
GROUP BY I.item_id, I.nom, YEAR(C.commandes), MONTH(C.commandes)

-- (Facultatif) On peut trier pour avoir un résultat plus chronologique.
ORDER BY I.nom, annee, mois;


-- Un utilisateur qui renseigne sa position (GPS) peut consulter la liste des restaurtants disponibles, dans un rayon de 2km, rangés par ordre croissant de distance.

SELECT R.nom, R.adresse, ST_Distance(R.coordonnees_gps, ST_SetSRID(ST_MakePoint([longitude_utilisateur], [latitude_utilisateur]), 4326)) as distance_en_m
FROM restaurants as R
WHERE ST_DWithin(
    R.coordonnees_gps,
    ST_SetSRID(ST_MakePoint([longitude_utilisateur], [latitude_utilisateur]), 4326),
    2000
);
ORDER BY distance_en_m ASC