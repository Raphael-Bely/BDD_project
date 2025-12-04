-- Get all comments for a restaurant with client names

SELECT 
    c.date_commentaire,
    c.contenu,
    c.note,
    cl.nom AS nom_client
FROM commentaires c
JOIN fidelite f ON c.fidelite_id = f.fidelite_id
JOIN clients cl ON f.client_id = cl.client_id
WHERE f.restaurant_id = ?
ORDER BY c.date_commentaire DESC