-- Check if a client email already exists in the database

SELECT client_id
FROM clients
WHERE email = ?
LIMIT 1