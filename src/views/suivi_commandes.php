<?php
// Contrôleur utilisé : suivi.php (qui inclut la vue) et marquer_recue.php (action du formulaire)

// Informations transmises (Vue -> Contrôleur via POST) :
// - commande_id : Identifiant de la commande envoyé au script 'marquer_recue.php' lorsque l'utilisateur confirme la réception.

// Informations importées (Contrôleur -> Vue) :
// - Données Session : client_id, client_nom, is_guest (pour l'affichage conditionnel du header et des alertes).
// - stmt (PDOStatement) : Liste des commandes en cours, contenant pour chacune :
//      - commande_id, date_commande, etat (statut interne), prix_total
//      - restaurant_nom, restaurant_adresse, heure_retrait
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de commande</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #f8f9fa;
            --text-main: #2c3e50;
            --text-muted: #6c757d;
            --primary-color: #1a1a1a;
            --accent-color: #e67e22;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --info-bg: #e3f2fd;
            --info-text: #0d47a1;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding: 20px 30px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            flex-wrap: wrap;
            gap: 15px;
        }

        .user-info {
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-actions a {
            text-decoration: none;
            font-weight: 600;
            color: var(--text-main);
            transition: color 0.2s;
            font-size: 0.95rem;
        }

        .header-actions a:hover {
            color: var(--accent-color);
        }

        .header-actions a.active {
            color: var(--accent-color);
        }

        .btn-logout {
            color: var(--danger-color) !important;
        }

        .btn-logout:hover {
            text-decoration: underline;
        }

        .badge-guest {
            background-color: #fff3cd;
            color: #856404;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-left: 10px;
            border: 1px solid #ffeeba;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 30px;
            color: var(--text-main);
            border-left: 5px solid var(--accent-color);
            padding-left: 15px;
        }

        .info-box {
            background-color: var(--info-bg);
            color: var(--info-text);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid #bbdefb;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .info-box a {
            color: var(--info-text);
            font-weight: bold;
        }

        .commande-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            border: 1px solid rgba(0, 0, 0, 0.02);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .commande-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--hover-shadow);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid #f1f1f1;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .order-id {
            font-size: 1.25rem;
            font-weight: 800;
            margin: 0;
            color: var(--text-main);
        }

        .order-date {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .status-processing {
            background-color: #fff3cd;
            color: #856404;
        }

        .card-body {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: end;
            margin-bottom: 25px;
        }

        .info-row {
            margin-bottom: 8px;
            font-size: 0.95rem;
            color: var(--text-main);
        }

        .info-label {
            color: var(--text-muted);
            font-weight: 500;
            margin-right: 5px;
        }

        .total-price {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--accent-color);
            text-align: right;
        }

        .card-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding-top: 20px;
            border-top: 1px dashed #eee;
        }

        .btn-details {
            background-color: white;
            color: var(--text-main);
            border: 1px solid #e0e0e0;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-details:hover {
            background-color: #f8f9fa;
            border-color: #ccc;
        }

        .btn-recue {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(39, 174, 96, 0.2);
        }

        .btn-recue:hover {
            background-color: #219150;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(39, 174, 96, 0.3);
        }

        .no-commandes {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            color: var(--text-muted);
        }

        .btn-retour {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            transition: color 0.2s;
        }

        .btn-retour:hover {
            color: var(--primary-color);
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header-bar">
            <?php if (isset($_SESSION['client_id'])): ?>
                <div class="user-info">
                    Bonjour, <strong>&nbsp;<?= htmlspecialchars($_SESSION['client_nom']) ?></strong> !

                    <?php if (isset($_SESSION['is_guest']) && $_SESSION['is_guest']): ?>
                        <span class="badge-guest">Mode Invité</span>
                    <?php endif; ?>
                </div>

                <div class="header-actions">
                    <a href="index.php">Restaurants</a>
                    <a href="commande.php?client_id=<?= $_SESSION['client_id'] ?>">Panier</a>
                    <a href="suivi.php" class="active">Suivi</a>

                    <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                        <a href="historique.php">Historique</a>
                    <?php endif; ?>

                    <a href="logout.php" class="btn-logout">Se déconnecter</a>
                </div>
            <?php endif; ?>
        </div>

        <h2 class="section-title">📦 Suivi de mes commandes</h2>

        <?php if (isset($_SESSION['is_guest']) && $_SESSION['is_guest']): ?>
            <div class="info-box">
                <span style="font-size: 1.5rem;">ℹ️</span>
                <div>
                    <strong>Mode Invité Activé</strong>
                    <p style="margin: 5px 0 0 0; font-size: 0.95rem;">
                        Une fois que vous aurez confirmé la réception de votre commande, votre session invité sera terminée.
                        <br>
                        <a href="create_account.php">Créez un compte</a> pour conserver votre historique.
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <?php
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $etat_label = "En cours de préparation";
                $date_formatee = date('d/m/Y à H:i', strtotime($date_commande));

                echo "<div class='commande-card'>";

                echo "<div class='card-header'>";
                echo "<div>";
                echo "<h3 class='order-id'>Commande #{$commande_id}</h3>";
                echo "<div class='order-date'>📅 {$date_formatee}</div>";
                echo "</div>";
                echo "<span class='status-badge status-processing'>⏳ {$etat_label}</span>";
                echo "</div>";

                echo "<div class='card-body'>";
                echo "<div class='details-col'>";
                echo "<div class='info-row'><span class='info-label'>Restaurant :</span> <strong>{$restaurant_nom}</strong></div>";
                echo "<div class='info-row'><span class='info-label'>Adresse :</span> {$restaurant_adresse}</div>";

                if (!empty($heure_retrait)) {
                    $est_asap = isset($row['est_asap']) ? $row['est_asap'] : false;
                    if ($est_asap) {
                        echo "<div class='info-row'><span class='info-label'>Heure de livraison :</span> <strong>Dès que possible</strong></div>";
                    } else {
                        $retrait_formatee = date('H:i', strtotime($heure_retrait));
                        echo "<div class='info-row'><span class='info-label'>Heure de livraison :</span> <strong>{$retrait_formatee}</strong></div>";
                    }
                }
                echo "</div>";

                echo "<div class='total-price'>" . number_format($prix_total, 2, ',', ' ') . " €</div>";
                echo "</div>";

                echo "<div class='card-actions'>";
                echo "<a href='detail_commande.php?id={$commande_id}' class='btn-details'>📄 Détails</a>";

                echo "<form method='POST' action='marquer_recue.php' style='margin: 0;'>";
                echo "<input type='hidden' name='commande_id' value='{$commande_id}'>";
                echo "<button type='submit' class='btn-recue' onclick='return confirm(\"Confirmez-vous avoir reçu cette commande ?\");'>✅ J'ai reçu ma commande</button>";
                echo "</form>";
                echo "</div>";

                echo "</div>";
            }
        } else {
            echo "<div class='no-commandes'>";
            echo "<h3>Aucune commande en cours 📭</h3>";
            echo "<p>Vos commandes apparaîtront ici une fois validées.</p>";
            echo "<a href='index.php' style='color:var(--accent-color); font-weight:bold; text-decoration:none; margin-top:10px; display:inline-block;'>Commander maintenant →</a>";
            echo "</div>";
        }
        ?>

        <a href="index.php" class="btn-retour">← Retour aux restaurants</a>

    </div>

</body>

</html>