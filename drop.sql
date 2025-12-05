-- Drop all objects in dependency order (leaf tables first, then parents, then types)
-- This ensures clean removal without needing CASCADE

-- Drop leaf tables (referenced only, not referencing)
DROP TABLE IF EXISTS details_commande_formule CASCADE;
DROP TABLE IF EXISTS contenir_items CASCADE;
DROP TABLE IF EXISTS contenir_formules CASCADE;
DROP TABLE IF EXISTS commentaires CASCADE;
DROP TABLE IF EXISTS avoir_conditions_formules CASCADE;
DROP TABLE IF EXISTS avoir_proprietes_items CASCADE;
DROP TABLE IF EXISTS composer CASCADE;
DROP TABLE IF EXISTS etre_accompagne_de CASCADE;
DROP TABLE IF EXISTS avoir_horaires_ouverture CASCADE;
DROP TABLE IF EXISTS avoir_categories_restaurants CASCADE;
DROP TABLE IF EXISTS item_offert CASCADE;
DROP TABLE IF EXISTS pourcentage_remise CASCADE;

-- Drop intermediate tables
DROP TABLE IF EXISTS composer_formules CASCADE;
DROP TABLE IF EXISTS conditions_formules CASCADE;
DROP TABLE IF EXISTS remises CASCADE;
DROP TABLE IF EXISTS fidelite CASCADE;
DROP TABLE IF EXISTS commandes CASCADE;
DROP TABLE IF EXISTS formules CASCADE;
DROP TABLE IF EXISTS clients CASCADE;
DROP TABLE IF EXISTS horaires_ouverture CASCADE;
DROP TABLE IF EXISTS categories_restaurants CASCADE;
DROP TABLE IF EXISTS ingredients CASCADE;
DROP TABLE IF EXISTS proprietes_items CASCADE;
DROP TABLE IF EXISTS items CASCADE;
DROP TABLE IF EXISTS categories_items CASCADE;

-- Drop parent tables
DROP TABLE IF EXISTS restaurants CASCADE;

-- Drop functions (they reference tables)
DROP FUNCTION IF EXISTS obtenir_ou_creer_commande(integer, integer);
DROP FUNCTION IF EXISTS update_prix_commande_func();
DROP FUNCTION IF EXISTS ajouter_au_panier(integer, integer, integer);
DROP FUNCTION IF EXISTS ajouter_formule_complete(integer, integer, integer, integer[]);
DROP FUNCTION IF EXISTS supprimer_au_panier(integer, integer);
DROP FUNCTION IF EXISTS update_note_restaurant();

-- Drop types
DROP TYPE IF EXISTS etat_commande;
