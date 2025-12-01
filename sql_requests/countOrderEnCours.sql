SELECT COUNT(*) as nb FROM commandes 
                WHERE client_id = ? AND etat = 'en_livraison'