-- Check whether a loyalty account already exists for a customer in a restaurant.

SELECT fidelite_id, 
       points 
FROM fidelite
WHERE client_id = :client_id 
  AND restaurant_id = :restaurant_id