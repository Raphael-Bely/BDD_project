<style>
    .header-bar {
        background-color: #3498db;
        padding: 10px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-bar a {
        color: white;
        margin: 0 10px;
        text-decoration: none;
    }

    .header-bar a:hover {
        text-decoration: underline;
    }

    .commande-card {
        border: 1px solid #ccc;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .commande-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .commande-date {
        font-size: 0.9em;
        color: #666;
    }

    .commande-etat {
        padding: 5px 10px;
        border-radius: 3px;
        font-weight: bold;
        font-size: 0.85em;
    }

    .etat-terminee {
        background-color: #2ecc71;
        color: white;
    }

    .etat-livree {
        background-color: #27ae60;
        color: white;
    }

    .etat-annulee {
        background-color: #e74c3c;
        color: white;
    }

    .commande-details {
        margin-top: 10px;
    }

    .prix-total {
        font-size: 1.2em;
        font-weight: bold;
        color: #2c3e50;
        margin-top: 10px;
    }

    .no-commandes {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    .btn-retour {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-retour:hover {
        background-color: #2980b9;
    }
</style>

<div class="header-bar">
    <?php if (isset($_SESSION['client_id'])): ?>
        <p>Bonjour, <strong><?= htmlspecialchars($_SESSION['client_nom']) ?></strong> !</p>

        <div>
            <a href="index.php">Restaurants</a>
            <a href="commande.php?client_id=<?= $_SESSION['client_id'] ?>">Ma commande actuelle</a>
            <a href="historique.php">Historique</a>
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
        $etat = $est_acheve ? 'Terminée' : 'En cours';
        $etat_class = $est_acheve ? 'etat-terminee' : 'etat-en-cours';

        // Formater la date
        $date_formatee = date('d/m/Y à H:i', strtotime($date_commande));

        echo "<div class='commande-card'>";
        echo "<div class='commande-header'>";
        echo "<div>";
        echo "<h3>{$restaurant_nom}</h3>";
        echo "<p class='commande-date'>Commandé le {$date_formatee}</p>";
        echo "</div>";
        echo "<span class='commande-etat {$etat_class}'>" . ucfirst($etat) . "</span>";
        echo "</div>";

        echo "<div class='commande-details'>";
        echo "<p><strong>Restaurant :</strong> {$restaurant_nom}</p>";
        echo "<p><strong>Adresse :</strong> {$restaurant_adresse}</p>";
        echo "</div>";

        echo "<div class='prix-total'>";
        echo "Prix total : " . number_format($prix_total, 2, ',', ' ') . " €";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<div class='no-commandes'>";
    echo "<p>😔 Vous n'avez pas encore d'historique de commandes.</p>";
    echo "<p>Commencez par commander dans l'un de nos restaurants !</p>";
    echo "</div>";
}
?>

<a href="index.php" class="btn-retour">← Retour aux restaurants</a>