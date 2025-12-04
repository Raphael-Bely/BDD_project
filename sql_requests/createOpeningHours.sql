-- Create a new opening hours time slot

INSERT INTO horaires_ouverture (jour_semaine, heure_ouverture, heure_fermeture) 
VALUES (?, ?, ?) 
RETURNING horaire_ouverture_id