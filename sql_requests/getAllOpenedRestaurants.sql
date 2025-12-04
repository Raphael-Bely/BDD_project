-- Get all restaurants that are currently open (based on current day and time)

SELECT DISTINCT 
    r.restaurant_id, 
    r.nom, 
    r.adresse,
    r.note_moyenne,
    r.nb_avis       
FROM restaurants r
LEFT JOIN avoir_horaires_ouverture aho ON r.restaurant_id = aho.restaurant_id
LEFT JOIN horaires_ouverture ho ON aho.horaire_ouverture_id = ho.horaire_ouverture_id
WHERE ho.horaire_ouverture_id IS NULL
   OR (ho.jour_semaine = EXTRACT(ISODOW FROM CURRENT_DATE)
       AND CURRENT_TIME BETWEEN ho.heure_ouverture AND ho.heure_fermeture)
ORDER BY r.nom ASC