<?php
// Contrôleur utilisé : detail_commande.php
// Informations transmises (Vue -> Contrôleur via GET) :
// - id (depuis l'URL) : L'identifiant unique de la commande spécifique à afficher.

// Informations importées (Contrôleur -> Vue) :
// - commande_info : Tableau associatif contenant les détails principaux de la commande (nom du restaurant, adresse, date, prix total, heure de retrait).
// - liste_articles : Tableau des articles individuels commandés (nom, prix, quantité, spécifications).
// - liste_formules : Tableau structuré des formules commandées (nom du menu, prix, liste des items composants).
// - client_id (via Session) : Utilisé pour vérifier la propriété de la commande et afficher les liens de navigation.
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de la commande</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #374151;
            margin: 0;
            padding: 40px 20px;
            line-height: 1.6;
        }

        .header-nav {
            max-width: 800px;
            margin: 0 auto 20px;
        }

        .header-nav a {
            display: inline-block;
            margin-right: 15px;
            text-decoration: none;
            color: #3498db;
            font-weight: 600;
        }

        .header-nav a:hover {
            text-decoration: underline;
        }

        .commande-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            max-width: 800px;
            margin: 0 auto 30px auto;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .commande-header {
            background-color: #f9fafb;
            padding: 24px;
            border-bottom: 2px solid #e5e7eb;
        }

        .commande-header h2 {
            margin: 0 0 8px 0;
            font-size: 1.5rem;
            color: #111827;
        }

        .commande-info {
            padding: 20px 24px;
            background-color: #ffffff;
        }

        .commande-info p {
            margin: 8px 0;
            font-size: 0.95rem;
        }

        .label {
            font-weight: 600;
            color: #6b7280;
        }

        h3 {
            color: #111827;
            font-size: 1.2rem;
            margin: 20px 24px 12px 24px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .sub-table-header {
            background-color: #e67e22;
            color: white;
            font-weight: 600;
        }

        .sub-table-header th {
            padding: 12px;
            text-align: left;
            font-size: 0.9rem;
        }

        tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody td {
            padding: 12px;
            font-size: 0.9rem;
        }

        .composition-text {
            font-size: 0.85em;
            color: #6b7280;
            font-style: italic;
            margin-top: 4px;
        }

        .resume-prix {
            background-color: #f9fafb;
            padding: 20px 24px;
            border-top: 2px solid #e5e7eb;
        }

        .resume-prix p {
            margin: 8px 0;
            font-size: 1rem;
            display: flex;
            justify-content: space-between;
        }

        .resume-prix .total {
            font-size: 1.4rem;
            font-weight: 700;
            color: #111827;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #d1d5db;
        }

        .actions {
            padding: 20px 24px;
            display: flex;
            gap: 12px;
        }

        .btn-annuler {
            background-color: #ef4444;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .btn-annuler:hover {
            background-color: #dc2626;
        }

        .btn-confirmer {
            background-color: #10b981;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .btn-confirmer:hover {
            background-color: #059669;
        }

        .empty-message {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-message h2 {
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        .empty-message p {
            font-size: 1rem;
        }
    </style>
</head>

<body>

    <div class="header-nav">
        <a href="historique.php">← Retour à l'historique</a>
        <?php if (isset($_SESSION['client_id'])): ?>
            <a href="index.php">Restaurants</a>
        <?php endif; ?>
    </div>

    <?php
    $dateCmd = new DateTime($commande_info['date_commande']);
    $dateAffichee = $dateCmd->format('d/m/Y à H:i');

    echo "<div class='commande-card'>";

    echo "<div class='commande-header'>";
    echo "<h2>Commande #" . htmlspecialchars($commande_info['commande_id']) . "</h2>";
    echo "</div>";

    echo "<div class='commande-info'>";
    echo "<p><span class='label'>Restaurant :</span> " . htmlspecialchars($commande_info['restaurant_nom']) . "</p>";
    echo "<p><span class='label'>Adresse :</span> " . htmlspecialchars($commande_info['restaurant_adresse']) . "</p>";
    echo "<p><span class='label'>Date :</span> " . $dateAffichee . "</p>";

    if (!empty($commande_info['heure_retrait'])) {
        $est_asap = isset($commande_info['est_asap']) ? $commande_info['est_asap'] : false;
        if ($est_asap) {
            echo "<p><span class='label'>Retrait :</span> Dès que possible</p>";
        } else {
            $retraitCmd = new DateTime($commande_info['heure_retrait']);
            echo "<p><span class='label'>Retrait :</span> " . $retraitCmd->format('H:i') . "</p>";
        }
    }
    echo "</div>";

    // formules
    if (!empty($commande_info['liste_formules'])) {
        echo "<h3>🍱 Formules</h3>";

        echo "<table>";
        echo "<thead><tr class='sub-table-header'>
                <th>Menu</th>
                <th>Composition</th>
                <th style='text-align: right;'>Prix</th>
              </tr></thead>";
        echo "<tbody>";

        foreach ($commande_info['liste_formules'] as $formule) {
            $composition = !empty($formule['items'])
                ? implode(', ', $formule['items'])
                : '<em>Aucun item</em>';

            echo "<tr>";
            echo "<td><strong>" . htmlspecialchars($formule['nom']) . "</strong></td>";
            echo "<td class='composition-text'>" . $composition . "</td>";
            echo "<td style='text-align: right;'>" . number_format($formule['prix'], 2, ',', ' ') . " €</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    }

    // articles
    if (!empty($commande_info['liste_articles'])) {
        echo "<h3>🍽️ Articles à la carte</h3>";

        echo "<table>";
        echo "<thead><tr class='sub-table-header'>
                <th>Article</th>
                <th style='text-align: center;'>Quantité</th>
                <th style='text-align: right;'>Prix unitaire</th>
                <th style='text-align: right;'>Total</th>
              </tr></thead>";
        echo "<tbody>";

        foreach ($commande_info['liste_articles'] as $article) {
            $total_ligne = $article['prix'] * $article['quantite'];

            echo "<tr>";
            echo "<td><strong>" . htmlspecialchars($article['nom']) . "</strong>";
            if (!empty($article['specifications'])) {
                echo "<div class='composition-text'>" . htmlspecialchars($article['specifications']) . "</div>";
            }
            echo "</td>";
            echo "<td style='text-align: center;'>" . $article['quantite'] . "</td>";
            echo "<td style='text-align: right;'>" . number_format($article['prix'], 2, ',', ' ') . " €</td>";
            echo "<td style='text-align: right;'>" . number_format($total_ligne, 2, ',', ' ') . " €</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    }

    // prix
    echo "<div class='resume-prix'>";
    echo "<p class='total'>";
    echo "<span>Total</span>";
    echo "<span>" . number_format($commande_info['prix_total_remise'], 2, ',', ' ') . " €</span>";
    echo "</p>";
    echo "</div>";

    echo "</div>"; 
    ?>

</body>

</html>