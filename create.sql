CREATE EXTENSION postgis;

CREATE TABLE restaurants
(
    restaurant_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    coordonnees_gps geography(POINT, 4326) NOT NULL
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
    restaurant_id INT REFERENCES restaurants(restaurant_id),
    categorie_item_id INT REFERENCES categories_items(categorie_item_id)
);

CREATE TABLE proprietes_items
(
    propriete_items_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE avoir_proprietes_items
(
    item_id INT REFERENCES items(item_id),
    propriete_items_id INT REFERENCES proprietes_items(propriete_items_id),
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
    item_id INT REFERENCES items(item_id),
    ingredient_id INT REFERENCES ingredients(ingredient_id),
    quantite_g INT NOT NULL,
    PRIMARY KEY (item_id, ingredient_id)
);

CREATE TABLE etre_accompagne_de
(
    item_id1 INT REFERENCES items(item_id),
    item_id2 INT REFERENCES items(item_id),
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
    restaurant_id INT REFERENCES restaurants(restaurant_id),
    horaire_ouverture_id INT REFERENCES horaires_ouverture(horaire_ouverture_id),
    PRIMARY KEY (restaurant_id, horaire_ouverture_id)
);

CREATE TABLE categories_restaurants
(
    categorie_restaurant_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE avoir_categories_restaurants
(
    restaurant_id INT REFERENCES restaurants(restaurant_id),
    categorie_restaurant_id INT REFERENCES categories_restaurants(categorie_restaurant_id),
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
    client_id INT REFERENCES clients(client_id),
    restaurant_id INT REFERENCES restaurants(restaurant_id)
);

CREATE TABLE commentaires
(
    commentaire_id SERIAL PRIMARY KEY,
    date_commentaire TIMESTAMP NOT NULL,
    contenu TEXT NOT NULL,
    note INT CHECK (note >= 1 AND note <= 5),
    fidelite_id INT REFERENCES fidelite(fidelite_id)
);

CREATE TABLE formules
(
    formule_id SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    restaurant_id INT REFERENCES restaurants(restaurant_id)
);

CREATE TABLE composer_formules
(
    formule_id INT REFERENCES formules(formule_id),
    categorie_item_id INT REFERENCES categories_items(categorie_item_id),
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
    formule_id INT REFERENCES formules(formule_id),
    condition_formule_id INT REFERENCES conditions_formules(condition_formule_id),
    PRIMARY KEY (formule_id, condition_formule_id)
);

CREATE TABLE commandes
(
    commande_id SERIAL PRIMARY KEY,
    date_commande TIMESTAMP NOT NULL,
    prix_total_remise DECIMAL(10, 2) NOT NULL,
    client_id INT REFERENCES clients(client_id),
    restaurant_id INT REFERENCES restaurants(restaurant_id)
);

CREATE TABLE contenir_items
(
    commande_id INT REFERENCES commandes(commande_id),
    item_id INT REFERENCES items(item_id),
    quantite INT NOT NULL,
    specifications TEXT,
    PRIMARY KEY (commande_id, item_id)
);

CREATE TABLE contenir_formules
(
    commande_id INT REFERENCES commandes(commande_id),
    formule_id INT REFERENCES formules(formule_id),
    item_id INT REFERENCES items(item_id),
    quantite INT NOT NULL,
    PRIMARY KEY (commande_id, formule_id, item_id)
);

CREATE TABLE remises
(
    remise_id SERIAL PRIMARY KEY,
    type_remise VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    seuil_points INT NOT NULL,
    restaurant_id INT REFERENCES restaurants(restaurant_id)
);

CREATE TABLE item_offert
(
    remise_id INT REFERENCES remises(remise_id),
    item_id INT REFERENCES items(item_id),
    quantite INT NOT NULL,
    PRIMARY KEY (remise_id, item_id)
);

CREATE TABLE pourcentage_remise
(
    remise_id INT REFERENCES remises(remise_id),
    pourcentage DECIMAL(5, 2) NOT NULL,
    PRIMARY KEY (remise_id)
);