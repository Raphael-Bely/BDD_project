<?php
session_start();

// Initialisation du message d'erreur s'il existe dans l'URL
$error_msg = "";
if (isset($_GET['error']) && $_GET['error'] == 1) {
    $error_msg = "Compte introuvable. Vérifiez votre nom et votre email.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Client - T'Abeille</title>
    <style>
        /* --- Style Global (cohérent avec liste_ingredients.php) --- */
        body { 
            font-family: sans-serif; 
            padding: 20px; 
            max-width: 800px; 
            margin: auto; 
            background-color: #f9f9f9; 
            color: #333;
        }

        /* Bouton Retour */
        .retour-btn { 
            text-decoration: none; 
            color: #333; 
            font-weight: bold; 
            display: inline-block; 
            margin-bottom: 20px;
        }
        .retour-btn:hover { color: #555; }

        /* --- Carte de Connexion --- */
        .login-card {
            border: 1px solid #ddd;
            padding: 40px; /* Un peu plus d'espace que la carte nutrition */
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 450px; /* Largeur optimale pour un formulaire */
            margin: 50px auto; /* Centré verticalement */
        }

        h2 { 
            text-align: center; 
            margin-top: 0; 
            color: #2c3e50; 
            margin-bottom: 30px;
        }

        /* --- Champs du formulaire --- */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Empêche le padding de dépasser */
            font-size: 16px;
        }

        input:focus {
            border-color: #2c3e50;
            outline: none;
        }

        /* --- Bouton d'action --- */
        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #2c3e50; /* Même bleu foncé que tes titres */
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #1a252f;
        }

        /* --- Message d'erreur --- */
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 0.95em;
        }

        /* --- Liens bas de page --- */
        .footer-links {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
        }
        .footer-links a {
            color: #555;
            text-decoration: none;
            margin: 0 10px;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Suivi de Commande</h2>

        <?php if (!empty($error_msg)): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            
            <div class="form-group">
                <label for="nom">Votre Nom</label>
                <input type="text" id="nom" name="nom" placeholder="Ex: Dupont" required>
            </div>

            <div class="form-group">
                <label for="email">Votre Email</label>
                <input type="email" id="email" name="email" placeholder="Ex: jean.dupont@email.com" required>
            </div>

            <button type="submit" class="btn-submit" href="index.php">Se connecter</button>

        </form>
    </div>

</body>
</html>