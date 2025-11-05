--REQUETE CONSULTATION

--Liste des restaurants de chaque catégorie.

SELECT CR.*, R.* from categorieResto as CR
    JOIN lien_categorie_restaurant as L1 on L1.categorie_restaurant_id = CR.categorie_restaurant_id  
    JOIN restaurant as R on L1.restaurant_id = R.restaurant_id
    ORDER BY CR.categorie_restaurant_id;


--Liste des restaurants selon la disponibilité de plats de chaque catégorie.

SELECT CI.*,I.* from categorieItems as CI
    JOIN specifier as S on S.categorie_id = CI.categorie_id
    JOIN Items as I on I.items_id = S.items_id
    JOIN Restaurant as R on R.restaurant_id = I.restaurant_id
    WHERE I.disponibilite = TRUE;


--La liste des commandes passées par des clients sans compte.

SELECT C.* from Clients as Cl 
    JOIN Commandes as Co on Co.client_id = Cl.client_id
    WHERE Cl.client_id NOT IN (

            SELECT F.client_id from Fidelisation as F
    );


--REQUETE STATISTIQUE 

--La liste des clients avec un compte, avec le nombre de commandes qu’ils ont passé, et le montant total.

SELECT count(Co.commande_id), Cl.* from Client as Cl 
    JOIN Fidelisation as F on F.client_id = Cl.client_id
    JOIN Commandes as Co on Co.client_id = Cl.client_id
    GROUP BY Cl.commande_id