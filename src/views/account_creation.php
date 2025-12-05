<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Compte</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
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
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            height: 80px;
        }

        input:focus,
        textarea:focus {
            border-color: #3498db;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background-color: #2980b9;
        }

        .error-msg {
            background-color: #fce4e4;
            color: #c0392b;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #f1aeb5;
            font-size: 0.9em;
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
        <h2>Créer un compte 📝</h2>

        <?php if (isset($error_message) && !empty($error_message)): ?>
            <div class="error-msg">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">

            <div class="form-group">
                <label for="nom">Nom complet :</label>
                <input type="text" id="nom" name="nom"
                    value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>" placeholder="Votre nom"
                    required>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone :</label>
                <input type="text" id="telephone" name="telephone"
                    value="<?= isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '' ?>"
                    placeholder="06 12 34 56 78" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse Email :</label>

                <?php if (isset($error_message_email) && !empty($error_message_email)): ?>
                    <div style="color: #c0392b; font-size: 0.85em; margin-bottom: 5px;">
                        ⚠️ <?= htmlspecialchars($error_message_email) ?>
                    </div>
                <?php endif; ?>

                <input type="email" id="email" name="email"
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                    placeholder="exemple@email.com" required>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse postale :</label>
                <textarea id="adresse" name="adresse" placeholder="Numéro, Rue, Ville..."
                    required><?= isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : '' ?></textarea>
            </div>

            <button type="submit">S'inscrire</button>
        </form>

        <a href="login.php" class="back-link">J'ai déjà un compte ? Se connecter</a>
        <a href="index.php" class="back-link">Retour à l'accueil</a>
    </div>

</body>

</html>