<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commander en tant qu'invité</title>
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
            margin-bottom: 10px;
        }

        .subtitle {
            color: #7f8c8d;
            font-size: 0.9em;
            margin-bottom: 25px;
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

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        .error-message {
            background-color: #e74c3c;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .info-box {
            background-color: #e8f4f8;
            border-left: 4px solid #3498db;
            padding: 12px;
            margin-bottom: 20px;
            text-align: left;
            font-size: 0.9em;
            color: #2c3e50;
        }

        .links {
            margin-top: 20px;
            font-size: 14px;
        }

        .links a {
            color: #3498db;
            text-decoration: none;
            margin: 0 10px;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .guest-icon {
            font-size: 3em;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="guest-icon">👤</div>
        <h2>Commander en tant qu'invité</h2>
        <p class="subtitle">Pas besoin de créer un compte !</p>

        <div class="info-box">
            ℹ️ <strong>Mode invité :</strong> Vous pourrez commander sans créer de compte. Votre session sera temporaire
            et vos données ne seront pas conservées après votre commande.
        </div>

        <?php if ($error_message): ?>
            <div class="error-message">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login_invite.php">
            <div class="form-group">
                <label for="adresse">Adresse de livraison *</label>
                <input type="text" id="adresse" name="adresse" placeholder="Votre adresse complète" required
                    autocomplete="street-address">
            </div>

            <button type="submit">🛒 Commencer à commander</button>
        </form>

        <div class="links">
            <a href="login.php">Se connecter avec un compte</a> |
            <a href="create_account.php">Créer un compte</a> |
            <a href="index.php">Retour</a>
        </div>
    </div>
</body>

</html>