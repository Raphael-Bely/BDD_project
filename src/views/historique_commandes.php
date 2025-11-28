<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Historique</title>
    <style>
        /* --- 1. Variables & Base (Identique aux autres pages) --- */
        :root {
            --bg-body: #f8f9fa;
            --text-main: #2c3e50;
            --text-muted: #7f8c8d;
            --primary-color: #1a1a1a;
            --accent-color: #e67e22; /* Orange marque */
            --success-color: #27ae60; /* Vert succès */
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 900px; /* Un peu plus étroit pour la lecture */
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* --- 2. Header Bar (Cohérent avec l'accueil) --- */
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

        .header-links a {
            text-decoration: none;
            margin-left: 20px;
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .header-links a:hover, .header-links a.active {
            color: var(--primary-color);
        }

        .btn-logout {
            color: #e74c3c !important;
        }
        .btn-logout:hover { text-decoration: underline; }

        /* --- 3. Titre --- */
        .page-title {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 30px;
            border-left: 5px solid var(--accent-color);
            padding-left: 15px;
            color: var(--text-main);
        }

        /* --- 4. Liste des Commandes (Cards) --- */
        .history-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Lien global qui enveloppe la carte */
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
            overflow: hidden;
        }

        .card-link:hover .commande-card {
            transform: translateY(-4px);
            box-shadow: var(--hover-shadow);
            border-color: rgba(0,0,0,0.05);
        }

        /* En-tête de la carte (Haut) */
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

        /* Badges d'état */
        .status-badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
</style>
<div class="header-bar">
    <?php if (isset($_SESSION['client_id'])): ?>
        <p>Bonjour, <strong><?= htmlspecialchars($_SESSION['client_nom']) ?></strong>
            <?php if (isset($_SESSION['is_guest']) && $_SESSION['is_guest']): ?>
                <span
                    style="background-color: #f39c12; padding: 2px 8px; border-radius: 3px; font-size: 0.8em; margin-left: 5px;">Mode
                    Invité</span>
            <?php endif; ?>
            !
        </p>

        <div>
            <a href="index.php">Restaurants</a>
            <a href="commande.php?client_id=<?= $_SESSION['client_id'] ?>">🛒 Mon panier</a>
            <a href="suivi.php">📦 Suivi</a>
            <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                <a href="historique.php">📋 Historique</a>
            <?php endif; ?>
            <a href="logout.php" style="color: #ffcccc;">Se déconnecter</a>
        </div>
    <?php endif; ?>
</div>

<h2>📋 Historique de mes commandes</h2>

<?php
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        // Déterminer l'état et la classe CSS
        // La colonne etat contient maintenant: 'en_commande', 'en_livraison', 'acheve'
        $etat_affiche = ($etat === 'acheve') ? 'Terminée' : 'En cours';
        $etat_class = ($etat === 'acheve') ? 'etat-terminee' : 'etat-en-cours';

        // Formater la date
        $date_formatee = date('d/m/Y à H:i', strtotime($date_commande));

        echo "<a href='detail_commande.php?id={$commande_id}' style='text-decoration: none; color: inherit;'>";
        echo "<div class='commande-card' style='cursor: pointer; transition: box-shadow 0.2s;' onmouseover='this.style.boxShadow=\"0 6px 20px rgba(0,0,0,0.15)\"' onmouseout='this.style.boxShadow=\"0 2px 8px rgba(0,0,0,0.08)\">";
        echo "<div class='commande-header'>";
        echo "<div>";
        echo "<h3>{$restaurant_nom}</h3>";
        echo "<p class='commande-date'>Commandé le {$date_formatee}</p>";
        echo "</div>";
        echo "<span class='commande-etat {$etat_class}>" . ucfirst($etat_affiche) . "</span>";
        echo "</div>";

        echo "<div class='commande-details'>";
        echo "<p><strong>Restaurant :</strong> {$restaurant_nom}</p>";
        echo "<p><strong>Adresse :</strong> {$restaurant_adresse}</p>";
        echo "</div>";

        echo "<div class='prix-total'>";
        echo "Prix total : " . number_format($prix_total, 2, ',', ' ') . " €";
        echo "</div>";
        echo "</div>";
        echo "</a>";
    }
} else {
    echo "<div class='no-commandes'>";
    echo "<p>😔 Vous n'avez pas encore d'historique de commandes.</p>";
    echo "<p>Commencez par commander dans l'un de nos restaurants !</p>";
    echo "</div>";
}
?>

<a href="index.php" class="btn-retour">← Retour aux restaurants</a>
