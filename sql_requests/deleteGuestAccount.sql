DELETE FROM clients 
                WHERE client_id = ? AND email LIKE 'invite_%@temp.local'