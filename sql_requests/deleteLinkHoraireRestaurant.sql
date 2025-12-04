-- Remove the link between a restaurant and an opening hours slot

DELETE FROM avoir_horaires_ouverture 
WHERE restaurant_id = ? 
  AND horaire_ouverture_id = ?