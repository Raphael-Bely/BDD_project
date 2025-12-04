-- Create a new fidelity account for a client at a specific restaurant

INSERT INTO fidelite (client_id, restaurant_id, points) 
VALUES (:client_id, :restaurant_id, :points)
RETURNING fidelite_id