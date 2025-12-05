<?php
// Contrôleur utilisé : login_restaurateur.php
// Informations transmises (Vue -> Contrôleur via POST) :
// - nom : Champ présent dans le formulaire (mais non utilisé pour la logique de connexion dans ce contrôleur).
// - email : L'adresse email utilisée comme identifiant principal.
// - mot_de_passe : Le mot de passe pour l'authentification.

// Informations importées (Contrôleur -> Vue) :
// - error : Variable contenant le message d'erreur à afficher en cas d'échec de connexion (ex: "Identifiants incorrects").
?>
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
        if (isset($error) && !empty($error)): ?>
            <div class="error-msg">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="login_restaurateur.php" method="POST">
            <div class="form-group">
                <label for="nom">Nom du restaurant :</label>
                <input type="text" id="nom" name="nom" placeholder="Ex: Burger King" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse Email :</label>
                <input type="email" id="email" name="email" placeholder="Ex: burger.king@email.com" required>
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe :</label>
                <input type="text" id="mot_de_passe" name="mot_de_passe" required>
            </div>

            <button type="submit">Se connecter</button>
        </form>

        <a href="index.php" class="back-link">← Retour à l'accueil</a>
    </div>

</body>

</html>