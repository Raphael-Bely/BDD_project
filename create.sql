CREATE EXTENSION IF NOT EXISTS postgis;

CREATE TABLE restaurants
(
    restaurant_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    coordonnees_gps geography(POINT, 4326) NOT NULL,
    restaurant_email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255)
);

CREATE INDEX idx_restaurants_coords ON restaurants USING GIST (coordonnees_gps);

CREATE TABLE categories_items
(
    categorie_item_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE items
(
    item_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    est_disponible BOOLEAN NOT NULL DEFAULT TRUE,
    restaurant_id INT REFERENCES restaurants(restaurant_id) ON DELETE CASCADE, 
    categorie_item_id INT REFERENCES categories_items(categorie_item_id) ON DELETE CASCADE 
);

CREATE TABLE proprietes_items
(
    propriete_items_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE avoir_proprietes_items
(
    item_id INT REFERENCES items(item_id) ON DELETE CASCADE,
    propriete_items_id INT REFERENCES proprietes_items(propriete_items_id) ON DELETE CASCADE,
    PRIMARY KEY (item_id, propriete_items_id)
);

CREATE TABLE ingredients
(
    ingredient_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    kcal_pour_100g INT NOT NULL,
    proteines_pour_100g DECIMAL(10, 2) NOT NULL
);

CREATE TABLE composer
(
    item_id INT REFERENCES items(item_id) ON DELETE CASCADE,
    ingredient_id INT REFERENCES ingredients(ingredient_id) ON DELETE CASCADE,
    quantite_g INT NOT NULL,
    PRIMARY KEY (item_id, ingredient_id)
);

CREATE TABLE etre_accompagne_de
(
    item_id1 INT REFERENCES items(item_id) ON DELETE CASCADE,
    item_id2 INT REFERENCES items(item_id) ON DELETE CASCADE,
    PRIMARY KEY (item_id1, item_id2)
);

CREATE TABLE horaires_ouverture
(
    horaire_ouverture_id SERIAL PRIMARY KEY,
    jour_semaine INT NOT NULL,
    heure_ouverture TIME NOT NULL,
    heure_fermeture TIME NOT NULL
);

CREATE TABLE avoir_horaires_ouverture
(
    restaurant_id INT REFERENCES restaurants(restaurant_id) ON DELETE CASCADE,
    horaire_ouverture_id INT REFERENCES horaires_ouverture(horaire_ouverture_id) ON DELETE CASCADE,
    PRIMARY KEY (restaurant_id, horaire_ouverture_id)
);

CREATE TABLE categories_restaurants
(
    categorie_restaurant_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE avoir_categories_restaurants
(
    restaurant_id INT REFERENCES restaurants(restaurant_id) ON DELETE CASCADE,
    categorie_restaurant_id INT REFERENCES categories_restaurants(categorie_restaurant_id) ON DELETE CASCADE,
    PRIMARY KEY (restaurant_id, categorie_restaurant_id)
);

CREATE TABLE clients
(
    client_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    adresse VARCHAR(255) NOT NULL
);

CREATE TABLE fidelite
(
    fidelite_id SERIAL PRIMARY KEY,
    points INT NOT NULL DEFAULT 0,
    client_id INT REFERENCES clients(client_id) ON DELETE CASCADE,
    restaurant_id INT REFERENCES restaurants(restaurant_id) ON DELETE CASCADE
);

CREATE TABLE commentaires
(
    commentaire_id SERIAL PRIMARY KEY,
    date_commentaire TIMESTAMP NOT NULL,
    contenu TEXT NOT NULL,
    note INT CHECK (note >= 1 AND note <= 5),
    fidelite_id INT REFERENCES fidelite(fidelite_id) ON DELETE CASCADE
);

CREATE TABLE formules
(
    formule_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    restaurant_id INT REFERENCES restaurants(restaurant_id) ON DELETE CASCADE
);

CREATE TABLE composer_formules
(
    formule_id INT REFERENCES formules(formule_id) ON DELETE CASCADE,
    categorie_item_id INT REFERENCES categories_items(categorie_item_id) ON DELETE CASCADE,
    PRIMARY KEY (formule_id, categorie_item_id)
);

CREATE TABLE conditions_formules
(
    condition_formule_id SERIAL PRIMARY KEY,
    jour_disponibilite INT NOT NULL,
    creneau_horaire TIME NOT NULL
);

CREATE TABLE avoir_conditions_formules
(
    formule_id INT REFERENCES formules(formule_id) ON DELETE CASCADE,
    condition_formule_id INT REFERENCES conditions_formules(condition_formule_id) ON DELETE CASCADE,
    PRIMARY KEY (formule_id, condition_formule_id)
);

-- Type ENUM pour les états de commande
CREATE TYPE etat_commande AS ENUM ('en_commande', 'en_livraison', 'acheve');

CREATE TABLE commandes
(
    commande_id SERIAL PRIMARY KEY,
    date_commande TIMESTAMP NOT NULL,
    heure_retrait TIMESTAMP,
    prix_total_remise DECIMAL(10, 2) DEFAULT 0,
    etat etat_commande DEFAULT 'en_commande' NOT NULL,
    client_id INT REFERENCES clients(client_id) ON DELETE CASCADE,
    restaurant_id INT REFERENCES restaurants(restaurant_id) ON DELETE CASCADE
);

CREATE TABLE contenir_items
(
    commande_id INT REFERENCES commandes(commande_id) ON DELETE CASCADE,
    item_id INT REFERENCES items(item_id) ON DELETE CASCADE,
    quantite INT NOT NULL,
    specifications TEXT,
    PRIMARY KEY (commande_id, item_id)
);

CREATE TABLE contenir_formules (
    id SERIAL PRIMARY KEY, 
    commande_id INT REFERENCES commandes(commande_id) ON DELETE CASCADE,
    formule_id INT REFERENCES formules(formule_id),
    quantite INT DEFAULT 1
);

CREATE TABLE details_commande_formule (
    contenir_formule_id INT REFERENCES contenir_formules(id) ON DELETE CASCADE,
    item_id INT REFERENCES items(item_id),
    PRIMARY KEY (contenir_formule_id, item_id)
);

CREATE TABLE remises
(
    remise_id SERIAL PRIMARY KEY,
    type_remise VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    seuil_points INT NOT NULL,
    restaurant_id INT REFERENCES restaurants(restaurant_id) ON DELETE CASCADE
);

CREATE TABLE item_offert
(
    remise_id INT REFERENCES remises(remise_id) ON DELETE CASCADE,
    item_id INT REFERENCES items(item_id) ON DELETE CASCADE,
    quantite INT NOT NULL,
    PRIMARY KEY (remise_id, item_id)
);

CREATE TABLE pourcentage_remise
(
    remise_id INT REFERENCES remises(remise_id) ON DELETE CASCADE,
    pourcentage DECIMAL(5, 2) NOT NULL,
    PRIMARY KEY (remise_id)
);


-- FUNCTIONS

-- SECONDAIRES

CREATE OR REPLACE FUNCTION obtenir_ou_creer_commande(
    p_client_id INT, 
    p_restaurant_id INT
) RETURNS INT AS $$
DECLARE
    v_commande_id INT;
BEGIN

    SELECT commande_id INTO v_commande_id
    FROM commandes 
    WHERE client_id = p_client_id 
      AND restaurant_id = p_restaurant_id 
      AND etat = 'en_commande'
    LIMIT 1;

    IF v_commande_id IS NULL THEN
        INSERT INTO commandes (client_id, restaurant_id, date_commande)
        VALUES (p_client_id, p_restaurant_id, NOW())
        RETURNING commande_id INTO v_commande_id;
    END IF;

    RETURN v_commande_id;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION update_prix_commande_func() RETURNS TRIGGER AS $$
DECLARE
    target_commande_id INT;
BEGIN
    IF (TG_OP = 'DELETE') THEN
        target_commande_id := OLD.commande_id;
    ELSE
        target_commande_id := NEW.commande_id;
    END IF;

    UPDATE commandes
    SET prix_total_remise = (
        COALESCE((
            SELECT SUM(i.prix * ci.quantite) 
            FROM contenir_items ci 
            JOIN items i ON ci.item_id = i.item_id 
            WHERE ci.commande_id = target_commande_id
        ), 0)
        +
        COALESCE((
            SELECT SUM(f.prix) 
            FROM contenir_formules cf 
            JOIN formules f ON cf.formule_id = f.formule_id 
            WHERE cf.commande_id = target_commande_id
        ), 0)
    )
    WHERE commande_id = target_commande_id;

    RETURN NULL; 
END;
$$ LANGUAGE plpgsql;


--PRIMAIRES

CREATE OR REPLACE FUNCTION ajouter_au_panier(
    p_client_id INT, 
    p_restaurant_id INT, 
    p_item_id INT
) RETURNS BOOLEAN AS $$
DECLARE
    v_commande_id INT;
    v_count INT;
BEGIN
    v_commande_id := obtenir_ou_creer_commande(p_client_id, p_restaurant_id);

    SELECT count(*) INTO v_count FROM contenir_items 
    WHERE commande_id = v_commande_id AND item_id = p_item_id;

    IF v_count > 0 THEN
        UPDATE contenir_items SET quantite = quantite + 1 
        WHERE commande_id = v_commande_id AND item_id = p_item_id;
    ELSE
        INSERT INTO contenir_items (commande_id, item_id, quantite)
        VALUES (v_commande_id, p_item_id, 1);
    END IF;

    -- le trigger recalcule le prix
    
    RETURN TRUE;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION ajouter_formule_complete(
    p_client_id INT,
    p_restaurant_id INT,
    p_formule_id INT,
    p_items_ids INT[]
) RETURNS BOOLEAN AS $$
DECLARE
    v_commande_id INT;
    v_contenir_formule_id INT;
BEGIN
    v_commande_id := obtenir_ou_creer_commande(p_client_id, p_restaurant_id);

    INSERT INTO contenir_formules (commande_id, formule_id)
    VALUES (v_commande_id, p_formule_id)
    RETURNING id INTO v_contenir_formule_id; 

    INSERT INTO details_commande_formule (contenir_formule_id, item_id)
    SELECT v_contenir_formule_id, unnest(p_items_ids);

    -- Le trigger s'occupe du prix

    RETURN TRUE;
END;
$$ LANGUAGE plpgsql;

-- TRIGGERS

-- Trigger sur les items 
DROP TRIGGER IF EXISTS trg_update_prix_items ON contenir_items;
CREATE TRIGGER trg_update_prix_items
AFTER INSERT OR UPDATE OR DELETE ON contenir_items
FOR EACH ROW
EXECUTE FUNCTION update_prix_commande_func();

-- Trigger sur les formules
DROP TRIGGER IF EXISTS trg_update_prix_formules ON contenir_formules;
CREATE TRIGGER trg_update_prix_formules
AFTER INSERT OR UPDATE OR DELETE ON contenir_formules
FOR EACH ROW
EXECUTE FUNCTION update_prix_commande_func();