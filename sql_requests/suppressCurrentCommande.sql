DELETE FROM commandes
WHERE commande_id = ?
AND est_acheve = FALSE;