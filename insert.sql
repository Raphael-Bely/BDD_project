-- pour insérer les coordonnées géographique : ST_SetSRID(ST_MakePoint(x, y), 4326)


INSERT INTO restaurants (nom, adresse, coordonnees_gps) VALUES
(
    'Le Procope', 
    '13 Rue de l''Ancienne Comédie, 75006 Paris', 
    ST_SetSRID(ST_MakePoint(2.3387, 48.8530), 4326)
),
(
    'Pizzeria Popolare', 
    '111 Rue Réaumur, 33000 Bordeaux', 
    ST_SetSRID(ST_MakePoint(2.3458, 48.8682), 4326)
),
(
    'Le Petit Cambodge', 
    '20 Rue Alibert, 75010 Paris', 
    ST_SetSRID(ST_MakePoint(2.3640, 48.8710), 4326)
),
(
    'Du Pain et des Idées', 
    '34 Rue Yves Toudic, 75010 Paris', 
    ST_SetSRID(ST_MakePoint(2.3614, 48.8708), 4326)
),
(
    'L''Ambroisie', 
    '9 Place des Vosges, 75004 Paris', 
    ST_SetSRID(ST_MakePoint(2.3656, 48.8554), 4326)
),
(
    'Septime', 
    '80 Rue de Charonne, 75011 Paris', 
    ST_SetSRID(ST_MakePoint(2.3804, 48.8530), 4326)
),
(
    'BAR de l''Enseirb',
    'Avenue du docteur Albert Schweitzer',
    ST_SetSRID(ST_MakePoint(-0.6026647, 44.8073656), 4326)
);

INSERT INTO categories_items (nom) VALUES
('Entrée'),
('Principal'),
('Accompagnement'),
('Dessert'),
('Boisson'),
('Sauce');


INSERT INTO items (nom, prix, est_disponible, restaurant_id, categorie_item_id) VALUES
-- Restaurant 1: Le Procope (Classique Français)
('Soupe à l''oignon', 14.50, TRUE, 1, 1),
('Coq au vin', 32.00, TRUE, 1, 2),
('Crème brûlée', 12.00, TRUE, 1, 4),
('Bouteille de Brouilly', 45.00, TRUE, 1, 5),

-- Restaurant 2: Pizzeria Popolare (Italien)
('Burrata e Pesto', 13.00, TRUE, 2, 1),
('Pizza Margherita', 12.00, TRUE, 2, 2),
('Tiramisu', 8.50, TRUE, 2, 4),
('Coca-Cola', 4.00, TRUE, 2, 5),
('Supplément Sauce Piquante', 1.50, TRUE, 2, 6),

-- Restaurant 3: Le Petit Cambodge (Cambodgien)
('Nems (Rouleaux impériaux)', 9.50, TRUE, 3, 1),
('Bo Bun Spécial', 16.50, TRUE, 3, 2),
('Riz gluant', 3.00, TRUE, 3, 3),
('Bière Angkor', 6.00, TRUE, 3, 5),

-- Restaurant 4: Du Pain et des Idées (Boulangerie)
('Croissant', 2.10, TRUE, 4, 4),
('Pain des Amis (part)', 4.50, TRUE, 4, 3),
('Café Expresso', 2.50, TRUE, 4, 5),
('Sandwich Jambon-Beurre', 6.00, TRUE, 4, 2),

-- Restaurant 5: L'Ambroisie (Haute Cuisine)
('Langoustines Royales', 85.00, TRUE, 5, 1),
('Poularde de Bresse Demi-Deuil', 140.00, TRUE, 5, 2),
('Tarte fine sablée au chocolat', 45.00, FALSE, 5, 4), -- Item non disponible

-- Restaurant 6: Septime (Moderne Français)
('Menu Dégustation 5 temps', 110.00, TRUE, 6, 2),
('Accord Vins Naturels', 70.00, TRUE, 6, 5),
('Plateau de Fromages Affinés', 22.00, TRUE, 6, 4);


INSERT INTO proprietes_items (nom) VALUES
('Végétarien'),
('Vegan'),
('Halal'),
('Casher'),
('Sans Gluten'),
('Sans Lactose'),
('Bio');

INSERT INTO avoir_proprietes_items (item_id, propriete_items_id) VALUES
-- (Propriétés: 1:Végétarien, 2:Vegan, 3:Halal, 5:Sans Gluten, 6:Sans Lactose, 7:Bio)

-- Le Procope (Restaurant 1)
(1, 1), -- Soupe à l'oignon (Végétarien)
(3, 1), -- Crème brûlée (Végétarien)
(3, 5), -- Crème brûlée (Sans Gluten)

-- Pizzeria Popolare (Restaurant 2)
(5, 1), -- Burrata e Pesto (Végétarien)
(6, 1), -- Pizza Margherita (Végétarien)
(7, 1), -- Tiramisu (Végétarien)
(8, 1), -- Coca-Cola (Végétarien)
(8, 2), -- Coca-Cola (Vegan)
(8, 3), -- Coca-Cola (Halal)
(8, 5), -- Coca-Cola (Sans Gluten)
(8, 6), -- Coca-Cola (Sans Lactose)
(9, 1), -- Supplément Sauce Piquante (Végétarien)
(9, 2), -- Supplément Sauce Piquante (Vegan)
(9, 5), -- Supplément Sauce Piquante (Sans Gluten)

-- Le Petit Cambodge (Restaurant 3)
(12, 1), -- Riz gluant (Végétarien)
(12, 2), -- Riz gluant (Vegan)
(12, 5), -- Riz gluant (Sans Gluten)
(12, 6), -- Riz gluant (Sans Lactose)
(13, 2), -- Bière Angkor (Vegan)

-- Du Pain et des Idées (Restaurant 4)
(14, 1), -- Croissant (Végétarien)
(15, 1), -- Pain des Amis (Végétarien)
(15, 2), -- Pain des Amis (Vegan)
(15, 6), -- Pain des Amis (Sans Lactose)
(16, 1), -- Café Expresso (Végétarien)
(16, 2), -- Café Expresso (Vegan)
(16, 5), -- Café Expresso (Sans Gluten)
(16, 6), -- Café Expresso (Sans Lactose)

-- L'Ambroisie (Restaurant 5)
(20, 1), -- Tarte fine sablée (Végétarien)

-- Septime (Restaurant 6)
(23, 1), -- Plateau de Fromages (Végétarien)
(23, 5), -- Plateau de Fromages (Sans Gluten)
(23, 7); -- Plateau de Fromages (Bio)


INSERT INTO ingredients (nom, kcal_pour_100g, proteines_pour_100g) VALUES
('Oignon', 40, 1.1),
('Bouillon de boeuf', 5, 0.5),
('Gruyère', 400, 29.0),
('Pain de campagne', 250, 9.0),
('Poulet (Coq)', 220, 25.0),
('Vin rouge', 85, 0.1),
('Lardons', 300, 15.0),
('Champignon de Paris', 22, 3.0),
('Crème liquide', 350, 2.5),
('Jaune d''oeuf', 320, 16.0),
('Sucre', 400, 0.0),
('Vanille', 288, 0.1),
('Burrata', 280, 18.0),
('Pesto', 450, 5.0),
('Tomate cerise', 18, 0.9),
('Pâte à pizza', 230, 8.0),
('Sauce tomate', 30, 1.2),
('Mozzarella', 280, 28.0),
('Basilic', 23, 3.2),
('Mascarpone', 400, 5.0),
('Café (expresso)', 1, 0.1),
('Biscuit cuillère', 380, 8.0),
('Cacao en poudre', 230, 20.0),
('Piment (oiseau)', 40, 1.9),
('Galette de riz', 330, 7.0),
('Porc haché', 250, 20.0),
('Crevette', 99, 24.0),
('Boeuf (filet)', 250, 26.0),
('Vermicelles de riz', 350, 7.0),
('Salade (Laitue)', 15, 1.2),
('Menthe', 44, 3.8),
('Cacahuète', 570, 26.0),
('Riz gluant', 130, 2.7),
('Beurre', 720, 0.9),
('Farine de blé', 360, 10.0),
('Jambon blanc', 145, 21.0),
('Langoustine', 90, 19.0),
('Chocolat noir', 550, 5.0),
('Fromage (divers)', 380, 25.0);


INSERT INTO composer (item_id, ingredient_id, quantite_g) VALUES
-- Item 1: Soupe à l'oignon
(1, 1, 150), -- Oignon
(1, 2, 200), -- Bouillon de boeuf
(1, 3, 50),  -- Gruyère
(1, 4, 30),  -- Pain de campagne

-- Item 2: Coq au vin
(2, 5, 250), -- Poulet (Coq)
(2, 6, 100), -- Vin rouge
(2, 7, 50),  -- Lardons
(2, 8, 50),  -- Champignon de Paris

-- Item 3: Crème brûlée
(3, 9, 100), -- Crème liquide
(3, 10, 40), -- Jaune d'oeuf
(3, 11, 20), -- Sucre

-- Item 5: Burrata e Pesto
(5, 13, 125), -- Burrata
(5, 14, 30),  -- Pesto
(5, 15, 50),  -- Tomate cerise

-- Item 6: Pizza Margherita
(6, 16, 150), -- Pâte à pizza
(6, 17, 80),  -- Sauce tomate
(6, 18, 100), -- Mozzarella
(6, 19, 5),   -- Basilic

-- Item 7: Tiramisu
(7, 20, 80),  -- Mascarpone
(7, 21, 20),  -- Café (expresso)
(7, 22, 40),  -- Biscuit cuillère
(7, 23, 5),   -- Cacao en poudre

-- Item 9: Supplément Sauce Piquante
(9, 24, 50),  -- Piment (oiseau)

-- Item 10: Nems
(10, 25, 20), -- Galette de riz
(10, 26, 40), -- Porc haché
(10, 27, 20), -- Crevette

-- Item 11: Bo Bun Spécial
(11, 28, 100), -- Boeuf (filet)
(11, 29, 80),  -- Vermicelles de riz
(11, 30, 50),  -- Salade (Laitue)
(11, 31, 5),   -- Menthe
(11, 32, 10),  -- Cacahuète

-- Item 12: Riz gluant
(12, 33, 150), -- Riz gluant

-- Item 14: Croissant
(14, 34, 40),  -- Beurre
(14, 35, 60),  -- Farine de blé

-- Item 15: Pain des Amis (part)
(15, 35, 200), -- Farine de blé

-- Item 16: Café Expresso
(16, 21, 15),  -- Café (expresso)

-- Item 17: Sandwich Jambon-Beurre
(17, 4, 100),  -- Pain de campagne
(17, 34, 20),  -- Beurre
(17, 36, 60),  -- Jambon blanc

-- Item 18: Langoustines Royales
(18, 37, 200), -- Langoustine

-- Item 20: Tarte fine sablée au chocolat
(20, 34, 50),  -- Beurre
(20, 35, 50),  -- Farine de blé
(20, 38, 80),  -- Chocolat noir

-- Item 23: Plateau de Fromages Affinés
(23, 39, 150); -- Fromage (divers)

INSERT INTO horaires_ouverture (jour_semaine, heure_ouverture, heure_fermeture) VALUES
-- Midi Semaine (Lun-Ven)
(1, '12:00:00', '14:30:00'), -- ID 1
(2, '12:00:00', '14:30:00'), -- ID 2
(3, '12:00:00', '14:30:00'), -- ID 3
(4, '12:00:00', '14:30:00'), -- ID 4
(5, '12:00:00', '14:30:00'), -- ID 5
-- Soir Semaine (Lun-Jeu)
(1, '19:00:00', '22:30:00'), -- ID 6
(2, '19:00:00', '22:30:00'), -- ID 7
(3, '19:00:00', '22:30:00'), -- ID 8
(4, '19:00:00', '22:30:00'), -- ID 9
-- Weekend (Ven-Sam)
(5, '19:00:00', '23:30:00'), -- ID 10 (Soir Ven)
(6, '12:00:00', '15:00:00'), -- ID 11 (Midi Sam)
(6, '19:00:00', '23:30:00'), -- ID 12 (Soir Sam)
-- Dimanche
(7, '12:00:00', '15:00:00'), -- ID 13 (Midi Dim)
(7, '19:00:00', '22:00:00'), -- ID 14 (Soir Dim)
-- Boulangerie (Mar-Sam)
(2, '07:00:00', '17:00:00'), -- ID 15
(3, '07:00:00', '17:00:00'), -- ID 16
(4, '07:00:00', '17:00:00'), -- ID 17
(5, '07:00:00', '17:00:00'), -- ID 18
(6, '07:00:00', '17:00:00'); -- ID 19

INSERT INTO avoir_horaires_ouverture (restaurant_id, horaire_ouverture_id) VALUES
-- Restaurant 1: Le Procope (Ouvert 7/7, Midi et Soir)
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (1, 10), (1, 11), (1, 12), (1, 13), (1, 14),

-- Restaurant 2: Pizzeria Popolare (Ouvert 7/7, Midi et Soir)
(2, 1), (2, 2), (2, 3), (2, 4), (2, 5), (2, 6), (2, 7), (2, 8), (2, 9), (2, 10), (2, 11), (2, 12), (2, 13), (2, 14),

-- Restaurant 3: Le Petit Cambodge (Midi/Soir en semaine, fermé Samedi midi et Dimanche)
(3, 1), (3, 2), (3, 3), (3, 4), (3, 5), (3, 6), (3, 7), (3, 8), (3, 9), (3, 10), (3, 12),

-- Restaurant 4: Du Pain et des Idées (Boulangerie, fermée Lun/Dim)
(4, 15), (4, 16), (4, 17), (4, 18), (4, 19),

-- Restaurant 5: L'Ambroisie (Haute cuisine, fermé Lundi, ouvert Mar-Dim)
(5, 2), (5, 3), (5, 4), (5, 5), (5, 7), (5, 8), (5, 9), (5, 10), (5, 11), (5, 12), (5, 13), (5, 14),

-- Restaurant 6: Septime (Moderne, Soir Mar-Sam, Midi Ven)
(6, 5), (6, 7), (6, 8), (6, 9), (6, 10), (6, 12);

INSERT INTO categories_restaurants (nom) VALUES
('Français'),
('Italien'),
('Cambodgien'),
('Bistrot / Brasserie'),
('Gastronomique'),
('Pizzeria'),
('Boulangerie'),
('Moderne'),
('Fast Food');

INSERT INTO avoir_categories_restaurants (restaurant_id, categorie_restaurant_id) VALUES
-- 1: Le Procope (Français, Brasserie)
(1, 1),
(1, 4),

-- 2: Pizzeria Popolare (Italien, Pizzeria)
(2, 2),
(2, 6),

-- 3: Le Petit Cambodge (Cambodgien)
(3, 3),

-- 4: Du Pain et des Idées (Boulangerie)
(4, 7),

-- 5: L'Ambroisie (Français, Gastronomique)
(5, 1),
(5, 5),

-- 6: Septime (Français, Gastronomique, Moderne)
(6, 1),
(6, 5),
(6, 8);


INSERT INTO clients (nom, email, adresse) VALUES
('Alice Dupont', 'alice.dupont@email.fr', '12 Rue de la Paix, 75002 Paris'),
('Benoit Martin', 'benoit.martin@email.com', '45 Avenue du Prado, 13008 Marseille'),
('Claire Petit', 'claire.petit@email.net', '8 Rue Sainte-Catherine, 33000 Bordeaux'),
('David Durand', 'david.durand@email.fr', '22 Place Bellecour, 69002 Lyon'),
('Elsa Lefebvre', 'elsa.lefebvre@email.org', '1 Boulevard de la Liberté, 59000 Lille'),
('Fabien Moreau', 'fabien.moreau@email.com', '7 Rue du Faubourg Saint-Honoré, 75008 Paris'),
('Garance Girard', 'garance.girard@email.fr', '19 Rue Alsace Lorraine, 31000 Toulouse'),
('Hugo Lambert', 'hugo.lambert@email.net', '5 Place Stanislas, 54000 Nancy'),
('Ines Roussel', 'ines.roussel@email.com', '30 Rue Nationale, 37000 Tours'),
('Julien Mercier', 'julien.mercier@email.fr', '10 Quai des Belges, 13001 Marseille');

INSERT INTO fidelite (client_id, restaurant_id, points) VALUES
-- Client 1 (Alice) est fidèle à la Pizzeria et à L'Ambroisie
(1, 2, 150),
(1, 5, 50),

-- Client 2 (Benoit) est fidèle à Septime
(2, 6, 80),

-- Client 3 (Claire) adore Le Petit Cambodge
(3, 3, 220),
(3, 4, 30),

-- Client 4 (David) est fidèle au Procope
(4, 1, 110),

-- Client 5 (Elsa) vient de s'inscrire à la Pizzeria
(5, 2, 10),

-- Client 6 (Fabien) à L'Ambroisie
(6, 5, 60),

-- Client 7 (Garance) au Petit Cambodge
(7, 3, 45),

-- Client 8 (Hugo) au Procope
(8, 1, 90),

-- Client 9 (Ines) à Septime
(9, 6, 25),

-- Client 10 (Julien) à la Pizzeria Popolare
(10, 2, 70);

INSERT INTO commentaires (date_commentaire, contenu, note, fidelite_id) VALUES
(
    NOW() - INTERVAL '10 days',
    'Pizza incroyable ! La meilleure margherita de Paris. L''ambiance est top, même si c''est un peu bruyant.',
    5,
    1 -- Commentaire d'Alice (Client 1) sur Pizzeria Popolare (Resto 2)
),
(
    NOW() - INTERVAL '2 days',
    'Un voyage dans le temps. La soupe à l''oignon était parfaite. Cadre historique et service impeccable.',
    5,
    6 -- Commentaire de David (Client 4) sur Le Procope (Resto 1)
),
(
    NOW() - INTERVAL '1 month',
    'Le Bo Bun est à tomber. J''y retourne toutes les semaines ! C''est frais, savoureux et copieux.',
    5,
    4 -- Commentaire de Claire (Client 3) sur Le Petit Cambodge (Resto 3)
),
(
    NOW() - INTERVAL '15 days',
    'Expérience incroyable. Le menu dégustation était innovant et délicieux. L''accord vin était parfait.',
    5,
    3 -- Commentaire de Benoit (Client 2) sur Septime (Resto 6)
),
(
    NOW() - INTERVAL '5 days',
    'Beaucoup de monde, service un peu lent. Mais les pizzas étaient bonnes. Il faut réserver longtemps à l''avance.',
    3,
    12 -- Commentaire de Julien (Client 10) sur Pizzeria Popolare (Resto 2)
),
(
    NOW() - INTERVAL '3 days',
    'C''est bon, mais c''est cher pour ce que c''est. On paie pour l''histoire et le lieu, plus que pour la cuisine.',
    3,
    10 -- Commentaire de Hugo (Client 8) sur Le Procope (Resto 1)
),
(
    NOW() - INTERVAL '1 day',
    'Le Pain des Amis est le meilleur pain de Paris. Un must. Le croissant est aussi excellent.',
    5,
    5 -- Commentaire de Claire (Client 3) sur Du Pain et des Idées (Resto 4)
);

INSERT INTO formules (nom, prix, restaurant_id) VALUES
('Menu Procope (Midi)', 55.00, 1), -- ID 1 (Le Procope)
('Menu Popolare (Pizza)', 22.00, 2), -- ID 2 (Pizzeria Popolare)
('Menu Bo Bun Complet', 21.50, 3), -- ID 3 (Le Petit Cambodge)
('Formule Petit Déjeuner', 6.00, 4), -- ID 4 (Du Pain et des Idées)
('Menu Déjeuner Septime', 65.00, 6); -- ID 5 (Septime)


INSERT INTO composer_formules (formule_id, categorie_item_id) VALUES
-- Menu Procope (ID 1) = Entrée(1) + Principal(2) + Dessert(4)
(1, 1),
(1, 2),
(1, 4),

-- Menu Popolare (ID 2) = Entrée(1) + Principal(2) + Boisson(5)
(2, 1),
(2, 2),
(2, 5),

-- Menu Bo Bun Complet (ID 3) = Entrée(1) + Principal(2)
(3, 1),
(3, 2),

-- Formule Petit Déjeuner (ID 4) = Dessert(4) + Boisson(5)
(4, 4),
(4, 5),

-- Menu Déjeuner Septime (ID 5) = Entrée(1) + Principal(2) + Dessert(4)
(5, 1),
(5, 2),
(5, 4);


INSERT INTO conditions_formules (jour_disponibilite, creneau_horaire) VALUES
-- Service du Midi (Lundi - Vendredi)
(1, '12:00:00'), -- ID 1 (Lundi Midi)
(2, '12:00:00'), -- ID 2 (Mardi Midi)
(3, '12:00:00'), -- ID 3 (Mercredi Midi)
(4, '12:00:00'), -- ID 4 (Jeudi Midi)
(5, '12:00:00'), -- ID 5 (Vendredi Midi)

-- Service du Soir (Lundi - Jeudi)
(1, '19:00:00'), -- ID 6 (Lundi Soir)
(2, '19:00:00'), -- ID 7 (Mardi Soir)
(3, '19:00:00'), -- ID 8 (Mercredi Soir)
(4, '19:00:00'), -- ID 9 (Jeudi Soir)

-- Service du Matin (Mardi - Samedi, pour la boulangerie)
(2, '07:00:00'), -- ID 10 (Mardi Matin)
(3, '07:00:00'), -- ID 11 (Mercredi Matin)
(4, '07:00:00'), -- ID 12 (Jeudi Matin)
(5, '07:00:00'), -- ID 13 (Vendredi Matin)
(6, '07:00:00'); -- ID 14 (Samedi Matin)


INSERT INTO avoir_conditions_formules (formule_id, condition_formule_id) VALUES
-- Formule 1: 'Menu Procope (Midi)' -> Uniquement le midi, du Lundi au Vendredi
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),

-- Formule 2: 'Menu Popolare (Pizza)' -> Disponible Midi et Soir, Lundi au Jeudi
(2, 1), (2, 2), (2, 3), (2, 4), -- Midi
(2, 6), (2, 7), (2, 8), (2, 9), -- Soir

-- Formule 3: 'Menu Bo Bun Complet' -> Disponible Midi et Soir, Lundi au Jeudi
(3, 1), (3, 2), (3, 3), (3, 4), -- Midi
(3, 6), (3, 7), (3, 8), (3, 9), -- Soir

-- Formule 4: 'Formule Petit Déjeuner' -> Boulangerie (Resto 4)
-- Disponible le matin, du Mardi au Samedi (fermé Lundi)
(4, 10),
(4, 11),
(4, 12),
(4, 13),
(4, 14),

-- Formule 5: 'Menu Déjeuner Septime' -> Uniquement le midi, le Vendredi
-- (Septime n'est ouvert que le vendredi midi)
(5, 5);

-- ==============================================================================
-- PARTIE COMMANDES (CORRIGÉE : Utilisation de la colonne 'id' pour contenir_formules)
-- ==============================================================================

-- COMMANDE 1: Client 1 (Alice) achète 1 item à la Pizzeria (Resto 2)
INSERT INTO commandes (commande_id, date_commande, prix_total_remise, client_id, restaurant_id)
VALUES (1, NOW() - INTERVAL '10 days', 12.00, 1, 2);
INSERT INTO contenir_items (commande_id, item_id, quantite) VALUES (1, 6, 1);


-- COMMANDE 2: Client 3 (Claire) achète 2 items au Petit Cambodge (Resto 3)
INSERT INTO commandes (commande_id, date_commande, prix_total_remise, client_id, restaurant_id)
VALUES (2, NOW() - INTERVAL '9 days', 52.00, 3, 3);
INSERT INTO contenir_items (commande_id, item_id, quantite) VALUES (2, 10, 2), (2, 11, 2);


-- COMMANDE 3: Client 4 (David) achète 1 'Menu Procope' (Formule 1)
INSERT INTO commandes (commande_id, date_commande, prix_total_remise, client_id, restaurant_id)
VALUES (3, NOW() - INTERVAL '8 days', 55.00, 4, 1);

-- 1. Création du "Ticket Formule" (ID 1) -> ATTENTION : on utilise la colonne 'id'
INSERT INTO contenir_formules (id, commande_id, formule_id) VALUES (1, 3, 1);
-- 2. Ajout des items liés
INSERT INTO details_commande_formule (contenir_formule_id, item_id) VALUES 
(1, 1), (1, 2), (1, 3);


-- COMMANDE 4: Client 5 (Elsa) achète 1 'Formule Petit Déjeuner'
INSERT INTO commandes (commande_id, date_commande, prix_total_remise, client_id, restaurant_id)
VALUES (4, NOW() - INTERVAL '8 days', 6.00, 5, 4);

-- 1. Création du "Ticket Formule" (ID 2)
INSERT INTO contenir_formules (id, commande_id, formule_id) VALUES (2, 4, 4);
-- 2. Ajout des items liés
INSERT INTO details_commande_formule (contenir_formule_id, item_id) VALUES 
(2, 14), (2, 16);


-- COMMANDE 5: Client 2 (Benoit) achète 1 'Menu Déjeuner Septime' ET 1 item à la carte
INSERT INTO commandes (commande_id, date_commande, prix_total_remise, client_id, restaurant_id)
VALUES (5, NOW() - INTERVAL '7 days', 135.00, 2, 6);

-- Partie Formule (ID 3)
INSERT INTO contenir_formules (id, commande_id, formule_id) VALUES (3, 5, 5);
INSERT INTO details_commande_formule (contenir_formule_id, item_id) VALUES 
(3, 21), (3, 23);

-- Partie A La Carte
INSERT INTO contenir_items (commande_id, item_id, quantite) VALUES (5, 22, 1);


-- COMMANDE 6: Client 10 (Julien) achète 1 'Menu Popolare' ET 1 sauce
INSERT INTO commandes (commande_id, date_commande, prix_total_remise, client_id, restaurant_id)
VALUES (6, NOW() - INTERVAL '6 days', 23.50, 10, 2);

-- Partie Formule (ID 4)
INSERT INTO contenir_formules (id, commande_id, formule_id) VALUES (4, 6, 2);
INSERT INTO details_commande_formule (contenir_formule_id, item_id) VALUES 
(4, 5), (4, 6), (4, 8);

-- Partie A La Carte
INSERT INTO contenir_items (commande_id, item_id, quantite) VALUES (6, 9, 1);


-- COMMANDE 7: Client 1 (Alice) items à la carte
INSERT INTO commandes (commande_id, date_commande, prix_total_remise, client_id, restaurant_id)
VALUES (7, NOW() - INTERVAL '5 days', 32.50, 1, 2);
INSERT INTO contenir_items (commande_id, item_id, quantite) VALUES (7, 6, 2), (7, 7, 1);


-- COMMANDE 8: Client 6 (Fabien) item à L'Ambroisie
INSERT INTO commandes (commande_id, date_commande, prix_total_remise, client_id, restaurant_id)
VALUES (8, NOW() - INTERVAL '5 days', 85.00, 6, 5);
INSERT INTO contenir_items (commande_id, item_id, quantite) VALUES (8, 18, 1);


-- COMMANDE 9: Client 7 (Garance) items à la Boulangerie
INSERT INTO commandes (commande_id, date_commande, prix_total_remise, client_id, restaurant_id)
VALUES (9, NOW() - INTERVAL '4 days', 6.60, 7, 4);
INSERT INTO contenir_items (commande_id, item_id, quantite) VALUES (9, 14, 1), (9, 15, 1);


-- COMMANDE 10: Client 7 (Garance) achète 1 'Menu Bo Bun'
INSERT INTO commandes (commande_id, date_commande, prix_total_remise, client_id, restaurant_id)
VALUES (10, NOW() - INTERVAL '3 days', 21.50, 7, 3);

-- Formule (ID 5)
INSERT INTO contenir_formules (id, commande_id, formule_id) VALUES (5, 10, 3);
INSERT INTO details_commande_formule (contenir_formule_id, item_id) VALUES 
(5, 10), (5, 11);


-- COMMANDE 11: Client 8 (Hugo) items au Procope
INSERT INTO commandes (commande_id, date_commande, prix_total_remise, client_id, restaurant_id)
VALUES (11, NOW() - INTERVAL '2 days', 77.00, 8, 1);
INSERT INTO contenir_items (commande_id, item_id, quantite) VALUES (11, 2, 1), (11, 4, 1);


-- COMMANDE 12: Client 9 (Ines) item à Septime
INSERT INTO commandes (commande_id, date_commande, prix_total_remise, client_id, restaurant_id)
VALUES (12, NOW() - INTERVAL '1 day', 22.00, 9, 6);
INSERT INTO contenir_items (commande_id, item_id, quantite) VALUES (12, 23, 1);


-- AJUSTEMENT DES SÉQUENCES (Corrige aussi l'erreur sur le nom de la séquence)
-- Comme la colonne s'appelle 'id', la séquence par défaut est 'contenir_formules_id_seq'
SELECT setval('commandes_commande_id_seq', (SELECT MAX(commande_id) FROM commandes));
SELECT setval('contenir_formules_id_seq', (SELECT MAX(id) FROM contenir_formules));



INSERT INTO remises (type_remise, description, seuil_points, restaurant_id) VALUES
-- ID 1: 10% au Procope (Resto 1)
('pourcentage_remise', '10% de réduction sur votre prochaine addition', 100, 1),

-- ID 2: Tiramisu offert à la Pizzeria (Resto 2)
('item_offert', 'Un Tiramisu (Item 7) offert', 80, 2),

-- ID 3: Nems offerts au Petit Cambodge (Resto 3)
('item_offert', 'Une portion de Nems (Item 10) offerte', 120, 3),

-- ID 4: 5% à la Boulangerie (Resto 4)
('pourcentage_remise', '5% de réduction sur vos achats', 50, 4),

-- ID 5: 15% à L'Ambroisie (Resto 5)
('pourcentage_remise', '15% de réduction sur le menu', 300, 5),

-- ID 6: Fromages offerts chez Septime (Resto 6)
('item_offert', 'Un Plateau de Fromages (Item 23) offert', 200, 6),

-- ID 7: 20% à la Pizzeria (Resto 2) - un 2ème niveau de remise
('pourcentage_remise', '20% de réduction pour les super-fidèles', 500, 2);


INSERT INTO pourcentage_remise (remise_id, pourcentage) VALUES
(1, 10.00), -- 10%
(4, 5.00),  -- 5%
(5, 15.00), -- 15%
(7, 20.00); -- 20%


INSERT INTO item_offert (remise_id, item_id, quantite) VALUES
(2, 7, 1),  -- 1 Tiramisu (Item 7)
(3, 10, 1), -- 1 Portion de Nems (Item 10)
(6, 23, 1); -- 1 Plateau de Fromages (Item 23)