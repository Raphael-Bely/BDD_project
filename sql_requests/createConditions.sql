-- If the conditions for the formula do not exist, they are created here.

INSERT INTO conditions_formules (jour_disponibilite, creneau_horaire_debut, creneau_horaire_fin) 
VALUES (?, ?, ?) RETURNING condition_formule_id