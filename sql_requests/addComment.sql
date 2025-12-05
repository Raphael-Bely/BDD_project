-- Add customer comments with text content and a rating.
-- The relationship between the restaurant and the customer is established through the fidelite_id attribute.

INSERT INTO commentaires (date_commentaire, contenu, note, fidelite_id) 
VALUES (NOW(), ?, ?, ?)