-- Check if a fidelity account exists for a client at a specific restaurant

SELECT fidelite_id, points 
FROM fidelite
WHERE client_id = :client_id 
  AND restaurant_id = :restaurant_id