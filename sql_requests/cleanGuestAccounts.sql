-- Clean up guest accounts periodically (finished orders > 24h or no orders > 2h)

-- Delete guest clients with completed orders older than 24 hours
DELETE FROM clients 
WHERE email LIKE 'invite_%@temp.local'
  AND client_id IN (
      SELECT DISTINCT c.client_id 
      FROM commandes cmd
      JOIN clients c ON cmd.client_id = c.client_id
      WHERE c.email LIKE 'invite_%@temp.local'
        AND cmd.etat = 'acheve'
        AND cmd.date_commande < NOW() - INTERVAL '24 hours'
  )

-- Delete guest clients with no orders
DELETE FROM clients 
WHERE email LIKE 'invite_%@temp.local'
  AND client_id NOT IN (SELECT DISTINCT client_id FROM commandes)
