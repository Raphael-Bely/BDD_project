<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            /* Pour que le padding ne casse pas la largeur */
            font-size: 16px;
        }

        input:focus {
            border-color: #3498db;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #27ae60;
        }

        .error-msg {
            background-color: #fce4e4;
            color: #c0392b;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #f1aeb5;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #7f8c8d;
            text-decoration: none;
            font-size: 0.9em;
        }

        .back-link:hover {
            color: #2c3e50;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2>Connexion 🔐</h2>

        <?php
        // Affichage du message d'erreur si la variable existe et n'est pas vide
        if (isset($error_message) && !empty($error_message)): ?>
            <div class="error-msg">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="nom">Nom du compte :</label>
                <input type="text" id="nom" name="nom" placeholder="Ex: Dupont" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse Email :</label>
                <input type="email" id="email" name="email" placeholder="Ex: jean.dupont@email.com" required>
            </div>

            <button type="submit">Se connecter</button>
        </form>

        <div style="margin-top: 20px; text-align: center;">
            <a href="create_account.php" style="color: #3498db; text-decoration: none; font-size: 0.95em;">Créer un
                compte</a> |
            <a href="login_invite.php" style="color: #27ae60; text-decoration: none; font-size: 0.95em;">👤 Commander en
                tant qu'invité</a>
        </div>

        <a href="index.php" class="back-link">← Retour à l'accueil</a>
    </div>

</body>

</html>