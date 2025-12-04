SELECT ho.horaire_ouverture_id, ho.jour_semaine, ho.heure_ouverture, ho.heure_fermeture 
FROM horaires_ouverture ho
JOIN avoir_horaires_ouverture aho ON ho.horaire_ouverture_id = aho.horaire_ouverture_id
WHERE aho.restaurant_id = ?
ORDER BY ho.jour_semaine, ho.heure_ouverture