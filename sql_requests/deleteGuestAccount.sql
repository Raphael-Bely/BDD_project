-- Delete a specific guest account

DELETE FROM clients 
WHERE client_id = ? 
  AND email LIKE 'invite_%@temp.local'