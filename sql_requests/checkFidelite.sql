SELECT fidelite_id, points 
FROM fidelite 
WHERE client_id = :client_id AND restaurant_id = :restaurant_id;