<style>
    .header-bar {
        background-color: #3498db;
        padding: 15px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .header-bar a {
        color: white;
        margin: 0 10px;
        text-decoration: none;
        font-weight: 500;
    }

    .header-bar a:hover {
        text-decoration: underline;
    }

    .commande-card {
        border: 1px solid #ccc;
        padding: 20px;
        margin-bottom: 15px;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .commande-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e5e7eb;
    }

    .commande-date {
        font-size: 0.9em;
        color: #666;
    }

    .commande-etat {
        padding: 8px 15px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 0.9em;
    }

    .etat-en-cours {
        background-color: #f39c12;
        color: white;
    }

    .etat-livraison {
        background-color: #3498db;
        color: white;
    }

    .commande-details {
        margin-top: 10px;
        margin-bottom: 15px;
    }

    .commande-details p {
        margin: 5px 0;
    }

    .prix-total {
        font-size: 1.2em;
        font-weight: bold;
        color: #2c3e50;
        margin: 15px 0;
    }

    .btn-container {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .btn-recue {
        background-color: #27ae60;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .btn-recue:hover {
        background-color: #229954;
    }

    .btn-details {
        background-color: #3498db;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .btn-details:hover {
        background-color: #2980b9;
    }

    .no-commandes {
        text-align: center;
        padding: 40px;
        color: #666;
        background-color: #f9f9f9;
        border-radius: 8px;
    }

    .btn-retour {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #95a5a6;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-retour:hover {
        background-color: #7f8c8d;
    }

    .info-box {
        background-color: #e8f4f8;
        border-left: 4px solid #3498db;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
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
            <a href="index.php">🏠 Restaurants</a>
            <a href="commande.php?client_id=<?= $_SESSION['client_id'] ?>">🛒 Mon panier</a>
            <a href="suivi.php">📦 Suivi</a>
            <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                <a href="historique.php">📋 Historique</a>
            <?php endif; ?>
            <a href="logout.php" style="color: #ffcccc;">Se déconnecter</a>
        </div>
    <?php endif; ?>
</div>

<h2>📦 Suivi de mes commandes</h2>

<?php if (isset($_SESSION['is_guest']) && $_SESSION['is_guest']): ?>
    <div class="info-box">
        <strong>ℹ️ Mode Invité</strong>
        <p style="margin: 5px 0 0 0;">Une fois que vous aurez confirmé la réception de votre commande, votre session sera
            terminée.
            <a href="create_account.php" style="color: #0056b3; text-decoration: underline;">Créer un compte</a> pour
            conserver l'historique de vos commandes.
        </p>
    </div>
<?php endif; ?>

<?php
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        // Déterminer l'état
        $etat = "En cours de traitement";
        $etat_class = "etat-en-cours";

        // Formater la date
        $date_formatee = date('d/m/Y à H:i', strtotime($date_commande));

        echo "<div class='commande-card'>";
        echo "<div class='commande-header'>";
        echo "<div>";
        echo "<h3>Commande #{$commande_id}</h3>";
        echo "<p class='commande-date'>Commandée le {$date_formatee}</p>";
        echo "</div>";
        echo "<span class='commande-etat {$etat_class}'>{$etat}</span>";
        echo "</div>";

        echo "<div class='commande-details'>";
        echo "<p><strong>Restaurant :</strong> {$restaurant_nom}</p>";
        echo "<p><strong>Adresse :</strong> {$restaurant_adresse}</p>";
        if (!empty($heure_retrait)) {
            $retrait_formatee = date('H:i', strtotime($heure_retrait));
            echo "<p><strong>Heure de retrait prévue :</strong> {$retrait_formatee}</p>";
        }
        echo "</div>";

        echo "<div class='prix-total'>";
        echo "Prix total : " . number_format($prix_total, 2, ',', ' ') . " €";
        echo "</div>";

        echo "<div class='btn-container'>";
        echo "<a href='detail_commande.php?id={$commande_id}' class='btn-details'>📄 Voir les détails</a>";
        echo "<form method='POST' action='marquer_recue.php' style='margin: 0;'>";
        echo "<input type='hidden' name='commande_id' value='{$commande_id}'>";
        echo "<button type='submit' class='btn-recue' onclick='return confirm(\"Confirmer que vous avez bien reçu cette commande ?\");'>✓ J'ai reçu ma commande</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<div class='no-commandes'>";
    echo "<p>📭 Vous n'avez aucune commande en cours.</p>";
    echo "<p>Vos commandes en cours apparaîtront ici après confirmation.</p>";
    echo "</div>";
}
?>

<a href="index.php" class="btn-retour">← Retour aux restaurants</a>