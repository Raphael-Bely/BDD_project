-- Fonction pour nettoyer les comptes invités après confirmation de commande
-- À exécuter périodiquement ou lors de la déconnexion

-- Supprimer les clients invités dont les commandes sont terminées depuis plus de 24h
DELETE FROM clients 
WHERE email LIKE 'invite_%@temp.local'
AND client_id IN (
    SELECT DISTINCT c.client_id 
    FROM commandes cmd
    JOIN clients c ON cmd.client_id = c.client_id
    WHERE c.email LIKE 'invite_%@temp.local'
    AND cmd.etat = 'acheve'
    AND cmd.date_commande < NOW() - INTERVAL '24 hours'
);

-- Supprimer les clients invités sans commande depuis plus de 2 heures
DELETE FROM clients 
WHERE email LIKE 'invite_%@temp.local'
AND client_id NOT IN (SELECT DISTINCT client_id FROM commandes)
AND client_id IN (
    SELECT client_id FROM clients 
    WHERE email LIKE 'invite_%@temp.local'
    -- Si vous avez une colonne date_creation, utilisez-la, sinon suppression basée sur l'absence de commandes
);
