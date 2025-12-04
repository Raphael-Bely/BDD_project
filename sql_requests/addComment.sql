-- Add a new comment from a client with a fidelity account for a restaurant

INSERT INTO commentaires (date_commentaire, contenu, note, fidelite_id) 
VALUES (NOW(), ?, ?, ?)