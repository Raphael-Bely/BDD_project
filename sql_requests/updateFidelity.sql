-- Update fidelity points for a client at a restaurant

UPDATE fidelite 
SET points = points + :points
WHERE fidelite_id = :fidelite_id