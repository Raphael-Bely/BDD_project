-- Get client ID by name and email

SELECT client_id 
FROM clients 
WHERE nom = ? 
  AND email = ?