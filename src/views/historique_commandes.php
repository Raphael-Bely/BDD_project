<?php
// Contrôleur utilisé : historique.php
// Informations transmises (Vue -> Contrôleur via GET/POST) :
// - Aucune donnée directe (la vue ne fait qu'afficher des données existantes).
// - client_id (via Session) : Utilisé implicitement pour récupérer l'historique spécifique de l'utilisateur connecté.

// Informations importées (Contrôleur -> Vue) :
// - stmt : Objet PDOStatement contenant la liste des commandes passées (id commande, date, état, prix total, nom du restaurant, adresse).
// - client_id, client_nom, is_guest (via Session) : Utilisés pour la gestion de l'en-tête (barre de navigation).
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Historique</title>
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
            --info-color: #3498db;
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
        .header-actions a:hover { color: var(--accent-color); }
        .header-actions a.active { color: var(--accent-color); }

        .btn-logout { color: var(--danger-color) !important; }
        .btn-logout:hover { text-decoration: underline; }

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

        .history-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .commande-card {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid rgba(0,0,0,0.02);
            position: relative;
        }

        .card-link:hover .commande-card {
            transform: translateY(-4px);
            box-shadow: var(--hover-shadow);
            border-color: rgba(0,0,0,0.05);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            border-bottom: 1px solid #f1f1f1;
            padding-bottom: 15px;
        }

        .resto-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-main);
        }

        .order-date {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 4px;
            display: block;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-finished { background-color: #d1fae5; color: #065f46; } 
        .status-delivery { background-color: #dbeafe; color: #1e40af; } 
        .status-pending  { background-color: #ffedd5; color: #9a3412; } 

        .card-body {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .address-info {
            font-size: 0.95rem;
            color: var(--text-main);
            flex: 1;
        }
        .address-label { color: var(--text-muted); font-size: 0.85rem; display: block; margin-bottom: 2px;}

        .price-tag {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text-main);
            background: #f8f9fa;
            padding: 8px 16px;
            border-radius: 12px;
            white-space: nowrap;
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
        .btn-retour:hover { color: var(--primary-color); }

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
                <a href="suivi.php">Suivi</a>
                
                <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                    <a href="historique.php" class="active">Historique</a>
                <?php endif; ?>

                <a href="logout.php" class="btn-logout">Se déconnecter</a>
            </div>
        <?php endif; ?>
    </div>

    <h2 class="section-title">📋 Historique de mes commandes</h2>

    <div class="history-list">
        <?php
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                // transcription état de la commande => état affiché
                if ($etat === 'acheve') {
                    $etat_label = 'Terminée';
                    $etat_css = 'status-finished';
                } elseif ($etat === 'en_livraison') {
                    $etat_label = 'En Livraison';
                    $etat_css = 'status-delivery';
                } else {
                    $etat_label = 'En Préparation';
                    $etat_css = 'status-pending';
                }

                $date_formatee = date('d/m/Y à H:i', strtotime($date_commande));

                echo "<a href='detail_commande.php?id={$commande_id}' class='card-link'>";
                    echo "<div class='commande-card'>";

                        echo "<div class='card-header'>";
                            echo "<div>";
                                echo "<h3 class='resto-name'>{$restaurant_nom}</h3>";
                                echo "<span class='order-date'>Commande #{$commande_id} &bull; {$date_formatee}</span>";
                            echo "</div>";
                            echo "<span class='status-badge {$etat_css}'>{$etat_label}</span>";
                        echo "</div>";

                        echo "<div class='card-body'>";
                            echo "<div class='address-info'>";
                                echo "<span class='address-label'>Lieu de commande :</span>";
                                echo "📍 {$restaurant_adresse}";
                            echo "</div>";

                            echo "<div class='price-tag'>";
                                echo number_format($prix_total, 2, ',', ' ') . " €";
                            echo "</div>";
                        echo "</div>";

                    echo "</div>";
                echo "</a>";
            }
        } else {
            echo "<div class='no-commandes'>";
                echo "<h3>C'est bien vide ici... 😔</h3>";
                echo "<p>Vous n'avez pas encore d'historique de commandes.</p>";
                echo "<a href='index.php' style='color:var(--accent-color); font-weight:bold; text-decoration:none; margin-top:15px; display:inline-block;'>Commander maintenant →</a>";
            echo "</div>";
        }
        ?>
    </div>

    <a href="index.php" class="btn-retour">← Retour aux restaurants</a>

</div>

</body>
</html>