-- Authenticate a restaurant owner by email

SELECT restaurant_id, nom, mot_de_passe 
FROM restaurants 
WHERE restaurant_email = ?