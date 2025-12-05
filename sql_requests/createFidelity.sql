-- Creates a loyalty account between a customer and a restaurant.
-- Allows loyalty points to be recorded and comments to be written.

INSERT INTO fidelite (client_id, restaurant_id, points) 
VALUES (:client_id, :restaurant_id, :points)
RETURNING fidelite_id