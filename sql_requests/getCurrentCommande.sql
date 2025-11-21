SELECT c.*
FROM commandes as c
WHERE client_id = ?
AND est_acheve = FALSE
ORDER BY c.date_commande DESC;