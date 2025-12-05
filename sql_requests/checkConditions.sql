-- Checks for the existence of a condition for the formula (one day + one time slot)
-- in order to avoid repetition of these conditions.

SELECT condition_formule_id 
FROM conditions_formules 
WHERE jour_disponibilite = ? 
    AND creneau_horaire_debut = ? 
    AND creneau_horaire_fin = ?