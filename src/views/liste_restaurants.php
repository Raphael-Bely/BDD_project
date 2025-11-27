<style>
    /* --- CSS Existant --- */
    .restaurant-card {
        border: 1px solid #ddd;
        padding: 20px;
        margin-bottom: 15px;
        border-radius: 8px;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s;
    }

    .restaurant-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .restaurant-card a {
        font-weight: bold;
        text-decoration: none;
        color: #2c3e50;
        font-size: 1.2em;
    }

    /* --- NOUVEAU CSS POUR LES FILTRES --- */
    .filtres-container {
        margin: 20px 0;
        overflow-x: auto;
        /* Permet de scroller si trop de catégories */
        white-space: nowrap;
        padding-bottom: 10px;
    }

    .btn-filtre {
        display: inline-block;
        padding: 8px 16px;
        margin-right: 10px;
        border-radius: 20px;
        text-decoration: none;
        color: #555;
        background-color: #f1f1f1;
        font-size: 0.9em;
        transition: all 0.3s ease;
        border: 1px solid #ddd;
    }

    .btn-filtre:hover {
        background-color: #e2e6ea;
    }

    /* Style du bouton actif */
    .btn-filtre.actif {
        background-color: #e67e22;
        /* Orange UberMiam */
        color: white;
        border-color: #d35400;
    }

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f1f1;
    }

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
</style>

<!-- HEADER (Inchangé) -->
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
            <a href="commande.php?client_id=<?= $_SESSION['client_id'] ?>">🛒 Mon panier</a>
            <a href="suivi.php">📦 Suivi</a>
            <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                <a href="historique.php">📋 Historique</a>
            <?php endif; ?>
            <a href="logout.php" style="color: red;">Se déconnecter</a>
        </div>
    <?php else: ?>
        <div>
            <a href="login_invite.php"
                style="background-color: #27ae60; padding: 8px 15px; border-radius: 5px; margin-right: 10px;">👤 Commander
                en tant qu'invité</a>
            <a href="login.php">Se connecter</a> |
            <a href="create_account.php">Créer un compte</a>
        </div>
    <?php endif; ?>
</div>

<h2>Liste des Restaurants Disponibles 🍽️</h2>

<?php
// --- 1. RÉCUPÉRATION DES CATÉGORIES POUR LES BOUTONS ---
// On suppose que $db est déjà connecté via require 'config/Database.php' plus haut dans le contrôleur
$sql_cats = "SELECT * FROM categories_restaurants ORDER BY nom";
$stmt_cats = $db->query($sql_cats);
$categories = $stmt_cats->fetchAll(PDO::FETCH_ASSOC);

// Récupération de l'ID catégorie actuel (si cliqué)
$current_cat = isset($_GET['cat_id']) ? $_GET['cat_id'] : null;
?>

<!-- AFFICHAGE DES FILTRES -->
<div class="filtres-container">
    <!-- Bouton "Tous" -->
    <a href="index.php" class="btn-filtre <?= $current_cat === null ? 'actif' : '' ?>">
        Tout voir
    </a>

    <!-- Boucle sur les catégories -->
    <?php foreach ($categories as $cat): ?>
        <a href="index.php?cat_id=<?= $cat['categorie_restaurant_id'] ?>"
            class="btn-filtre <?= $current_cat == $cat['categorie_restaurant_id'] ? 'actif' : '' ?>">
            <?= htmlspecialchars($cat['nom']) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- LOGIQUE DE RÉCUPÉRATION DES RESTAURANTS -->
<?php
// Si une catégorie est sélectionnée, on filtre
if ($current_cat) {
    // Lire le fichier SQL de filtre
    $sql_filter = file_get_contents(__DIR__ . '/../../sql_requests/getRestaurantsByCategory.sql');
    $stmt = $db->prepare($sql_filter);
    $stmt->execute(['id_cat' => $current_cat]);
} else {
    // Sinon, on affiche tout (votre logique actuelle)
    // Assurez-vous que getAllRestaurants.sql est bien chargé avant
    $sql_all = file_get_contents(__DIR__ . '/../../sql_requests/getAllRestaurants.sql');
    $stmt = $db->query($sql_all);
}
?>

<!-- LISTE DES CARTES -->
<?php
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        echo "<div class='restaurant-card'>";
        echo "<a href='menu.php?id={$restaurant_id}'>{$nom}</a>";
        echo "<p style='color:#666; margin-top:5px;'>📍 {$adresse}</p>";

        // Petit bonus : Afficher la catégorie sur la carte aussi si disponible
        // (Nécessite que votre requête SQL getAllRestaurants récupère aussi la catégorie)
        echo "</div>";
    }
} else {
    echo "<div style='text-align:center; padding: 40px; color: #777;'>";
    echo "<p>Aucun restaurant trouvé pour cette catégorie. 😔</p>";
    echo "<a href='index.php'>Voir tous les restaurants</a>";
    echo "</div>";
}
?>