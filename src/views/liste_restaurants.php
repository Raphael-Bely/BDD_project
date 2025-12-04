<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ... VOS STYLES CSS PRÉCÉDENTS ... */
        /* Je remets le CSS essentiel pour la complétude du fichier */
        :root { --bg-body: #f8f9fa; --text-main: #2c3e50; --primary-color: #1a1a1a; --accent-color: #e67e22; --green-success: #27ae60; --red-closed: #e74c3c; --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); --hover-shadow: 0 12px 24px rgba(0, 0, 0, 0.15); }
        body { font-family: 'Inter', system-ui, sans-serif; background-color: var(--bg-body); color: var(--text-main); margin: 0; padding: 0; line-height: 1.6; }
        .container { max-width: 1100px; margin: 0 auto; padding: 40px 20px; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; padding: 20px 30px; background: white; border-radius: 16px; box-shadow: var(--card-shadow); flex-wrap: wrap; gap: 15px; }
        .header-actions { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
        .header-actions a { text-decoration: none; font-weight: 600; font-size: 0.95rem; }
        .btn-geo { cursor: pointer; padding: 8px 16px; border-radius: 50px; border: 1px solid #ddd; background: white; color: var(--text-main); font-weight: 600; font-size: 0.9rem; transition: all 0.2s; display: flex; align-items: center; gap: 5px; }
        .btn-geo:hover { border-color: var(--accent-color); color: var(--accent-color); background-color: #fff8f0; }
        .filtres-wrapper { margin-bottom: 40px; overflow-x: auto; white-space: nowrap; padding-bottom: 10px; scrollbar-width: none; -ms-overflow-style: none; }
        .filtres-wrapper::-webkit-scrollbar { display: none; }
        .btn-filtre { display: inline-block; padding: 10px 24px; margin-right: 12px; border-radius: 50px; text-decoration: none; color: #7f8c8d; background-color: #ffffff; border: 1px solid #e0e0e0; transition: all 0.3s ease; font-size: 0.95rem; }
        .btn-filtre:hover, .btn-filtre.actif { background-color: var(--primary-color); color: white; border-color: var(--primary-color); transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .section-header { display: flex; align-items: center; margin-bottom: 25px; margin-top: 40px; }
        .section-title { font-size: 1.8rem; font-weight: 800; margin: 0; color: var(--text-main); }
        .status-dot { width: 12px; height: 12px; border-radius: 50%; margin-right: 10px; display: inline-block; }
        .dot-open { background-color: var(--green-success); box-shadow: 0 0 10px rgba(39, 174, 96, 0.4); }
        .dot-closed { background-color: var(--red-closed); }
        .restaurant-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 40px; padding-bottom: 20px; align-items: start; }
        .restaurant-card { background: #ffffff; border-radius: 16px; padding: 25px; box-shadow: var(--card-shadow); transition: transform 0.3s ease, box-shadow 0.3s ease; display: flex; flex-direction: column; min-height: 180px; text-decoration: none; color: inherit; position: relative; overflow: hidden; }
        .restaurant-card:hover { transform: translateY(-5px); box-shadow: var(--hover-shadow); }
        .card-link-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; text-decoration: none; }
        .card-open { border-left: 5px solid var(--green-success); }
        .card-closed { border-left: 5px solid var(--red-closed); opacity: 0.8; filter: grayscale(30%); }
        .card-title { font-size: 1.35rem; font-weight: 700; color: var(--text-main); margin-bottom: 10px; display: block; line-height: 1.3; }
        .restaurant-card:hover .card-title { color: var(--accent-color); }
        .card-address { color: #7f8c8d; font-size: 0.9rem; margin-top: auto; margin-bottom: 0; }
        .distance-badge { display: inline-block; color: var(--green-success); font-weight: 700; font-size: 0.9rem; margin-bottom: 10px; background-color: #e8f8f0; padding: 4px 8px; border-radius: 4px; width: fit-content; }
        .status-badge { position: absolute; top: 20px; right: 20px; font-size: 0.8rem; font-weight: bold; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; }
        .badge-open { background: #e8f8f0; color: var(--green-success); }
        .badge-closed { background: #fce8e6; color: var(--red-closed); }
        .review-section { display: flex; align-items: center; margin-bottom: 15px; position: relative; z-index: 2; width: fit-content; padding: 5px 10px; margin-left: -10px; border-radius: 8px; cursor: pointer; transition: background-color 0.2s; text-decoration:none; }
        .review-section:hover { background-color: #f0f2f5; }
        .review-section:hover .btn-avis { background: #3498db; color: white; border-color: #3498db; }
        .stars { color: #f1c40f; font-size: 1.1em; letter-spacing: 2px; margin: 0; }
        .stars-empty { color: #ccc; }
        .btn-avis { text-decoration: none; color: #95a5a6; font-size: 0.9rem; margin-left: 10px; cursor: pointer; border: 1px solid #ddd; border-radius: 50%; width: 30px; height: 30px; display: inline-flex; justify-content: center; align-items: center; transition: all 0.2s; background: white; }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: none; justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 25px; border-radius: 10px; width: 400px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); position: relative; }
        .close-modal { position: absolute; top: 10px; right: 15px; font-size: 1.5rem; cursor: pointer; color: #999; }
    </style>
</head>
<body>

<div class="container">

    <div class="header-bar">
        <?php if ($est_connecte): ?>
            <div>
                Bonjour, <strong><?= htmlspecialchars($nom_client) ?></strong> ! 👋
                <?php if (isset($_SESSION['is_guest']) && $_SESSION['is_guest']): ?>
                    <span style="background-color: #f39c12; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8em; margin-left: 5px;">Mode Invité</span>
                <?php endif; ?>
            </div>
            <div class="header-actions">
                <button onclick="getLocation()" class="btn-geo">📍 Trouver les restos autour de moi</button>
                <a href="commande.php" style="color:var(--primary-color);">🛒 Mon Panier</a>
                <a href="suivi.php" style="color:var(--primary-color);">📦 Suivi</a>
                <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                    <a href="historique.php" style="color:var(--primary-color);">📋 Historique</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                    <a href="statistiques.php" style="background:#2c3e50; color:white; padding:8px 12px; border-radius:6px;">📊 Statistiques</a>
                <?php endif; ?>
                <a href="logout.php" style="color: #e74c3c;">Se déconnecter</a>
            </div>
        <?php else: ?>
            <div class="header-actions">
                <a href="login_invite.php" style="background-color: #27ae60; color: white; padding: 8px 12px; border-radius: 6px;">👤 Invité</a>
                <a href="login.php">Se connecter</a>
                <a href="create_account.php" style="background:var(--primary-color); color:white; padding:8px 15px; border-radius:8px; text-decoration:none;">Créer un compte</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="filtres-wrapper">
        <a href="index.php" class="btn-filtre <?= ($current_cat === null && $lat === null) ? 'actif' : '' ?>">Tout voir</a>
        <?php foreach ($categories as $cat): ?>
            <a href="index.php?cat_id=<?= $cat['categorie_restaurant_id'] ?>" 
               class="btn-filtre <?= $current_cat == $cat['categorie_restaurant_id'] ? 'actif' : '' ?>">
                <?= htmlspecialchars($cat['nom']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php
    // --- FONCTION D'AFFICHAGE COMMUNE ---
    function afficherCarte($row, $est_connecte, $isOpen = true) {
        $id = $row['restaurant_id'];
        $nom = htmlspecialchars($row['nom']);
        $adresse = htmlspecialchars($row['adresse']);
        $note = isset($row['note_moyenne']) ? $row['note_moyenne'] : 0;
        $nb_avis = isset($row['nb_avis']) ? $row['nb_avis'] : 0;
        
        $cssClass = $isOpen ? 'card-open' : 'card-closed';
        $badge = $isOpen ? "<span class='status-badge badge-open'>Ouvert</span>" : "<span class='status-badge badge-closed'>Fermé</span>";

        echo "<div class='restaurant-card $cssClass'>";
            
            // 1. LIEN GLOBAL (VERS MENU)
            echo "<a href='menu.php?id={$id}' class='card-link-overlay'></a>";

            echo $badge;

            echo "<div style='margin-bottom: 5px;'>"; 
                echo "<span class='card-title'>{$nom}</span>";
            echo "</div>";

            // 2. LIEN AVIS (VERS AVIS_RESTAURANT)
            echo "<a href='avis_restaurant.php?id={$id}' class='review-section' title='Lire les avis'>";
                echo "<span class='stars' style='margin-right:8px;'>"; 
                for($i=1; $i<=5; $i++) echo ($i <= round($note)) ? "★" : "<span class='stars-empty'>☆</span>";
                echo "</span>";
                echo "<span style='font-weight:bold; color:#2c3e50; margin-right:5px; font-size:0.95em;'>" . number_format($note, 1) . " / 5</span>";
                echo "<span style='font-size:0.85em; color:#95a5a6;'>(" . $nb_avis . ")</span>";
                
                // CRAYON MODAL (HORS DU LIEN AVIS, MAIS DANS LA ZONE VISUELLE)
                if ($est_connecte) {
                    echo "<div class='btn-avis' onclick='event.preventDefault(); event.stopPropagation(); openAvisModal($id, \"" . addslashes($nom) . "\")' title='Laisser un avis'>✎</div>";
                }
            echo "</a>";
            
            if (isset($row['distance_km'])) {
                $dist = number_format($row['distance_km'], 2); 
                echo "<span class='distance-badge'>🏃 à {$dist} km</span>";
            }

            echo "<p class='card-address'>📍 {$adresse}</p>";

        echo "</div>"; // Fin restaurant-card
    }

    // --- LOGIQUE PRINCIPALE ---
    
    // CAS 1 : RECHERCHE GPS (Affichage unique)
    if ($lat) {
        echo "<h2 class='section-title'>";
        echo htmlspecialchars($titre_special); 
        echo "</h2>";

        echo "<div class='restaurant-grid'>";
        if ($stmt && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                afficherCarte($row, $est_connecte, true);
            }
        } else {
            echo "<div style='grid-column: 1 / -1; text-align:center; color:#7f8c8d;'>Aucun restaurant trouvé.</div>";
        }
        echo "</div>";

    } 
    // CAS 2 : CATÉGORIE OU ACCUEIL (Affichage séparé Ouvert/Fermé)
    else {
        
        // Affichage du titre de catégorie si présent
        if (isset($stmt_cat)) { 
            // stmt_cat peut être un statement ou un tableau selon votre modèle
            if (is_object($stmt_cat)) { $catInfo = $stmt_cat->fetch(PDO::FETCH_ASSOC); $catName = $catInfo['nom']; }
            elseif (is_array($stmt_cat) && !empty($stmt_cat)) { $catName = $stmt_cat['nom']; } // Si fetch déjà fait
            
            if(isset($catName)) {
                echo "<h2 class='section-title'>Catégorie : " . htmlspecialchars($catName) . "</h2>";
            }
        }

        // A. OUVERTS
        echo "<div class='section-header'><span class='status-dot dot-open'></span><h2 class='section-title'>Ouvert maintenant</h2></div>";
        echo "<div class='restaurant-grid'>";
        if (isset($stmt_open) && $stmt_open->rowCount() > 0) {
            while ($row = $stmt_open->fetch(PDO::FETCH_ASSOC)) {
                afficherCarte($row, $est_connecte, true);
            }
        } else {
            echo "<div style='grid-column: 1 / -1; color:#7f8c8d;'>Aucun restaurant ouvert dans cette catégorie.</div>";
        }
        echo "</div>";

        // B. FERMÉS
        echo "<div class='section-header'><span class='status-dot dot-closed'></span><h2 class='section-title' >Actuellement fermé</h2></div>";
        echo "<div class='restaurant-grid'>";
        if (isset($stmt_close) && $stmt_close->rowCount() > 0) {
            while ($row = $stmt_close->fetch(PDO::FETCH_ASSOC)) {
                afficherCarte($row, $est_connecte, false);
            }
        } else {
            echo "<div style='grid-column: 1 / -1; color:#7f8c8d;'>Aucun restaurant fermé dans cette catégorie.</div>";
        }
        echo "</div>";
    }
    ?>

</div>

<div id="modalAvis" class="modal-overlay">
    <div class="modal-content">
        <span class="close-modal" onclick="closeAvisModal()">&times;</span>
        <h3 id="modalRestoName">Noter ce restaurant</h3>
        <form action="ajout_avis.php" method="POST">
            <input type="hidden" name="restaurant_id" id="modalRestoId">
            <div style="margin-bottom:15px;">
                <label>Votre note :</label>
                <select name="note" style="width:100%; padding:8px;">
                    <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                    <option value="4">⭐⭐⭐⭐ (Très bon)</option>
                    <option value="3">⭐⭐⭐ (Correct)</option>
                    <option value="2">⭐⭐ (Moyen)</option>
                    <option value="1">⭐ (Mauvais)</option>
                </select>
            </div>
            <div style="margin-bottom:15px;">
                <label>Votre commentaire :</label>
                <textarea name="contenu" rows="4" style="width:100%; padding:8px;" placeholder="C'était délicieux..." required></textarea>
            </div>
            <button type="submit" style="width:100%; padding:10px; background:#27ae60; color:white; border:none; border-radius:5px; cursor:pointer;">Publier l'avis</button>
        </form>
    </div>
</div>

<script>
    // Scripts inchangés
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("La géolocalisation n'est pas supportée.");
        }
    }
    function showPosition(position) {
        window.location.href = "index.php?action=geo&lat=" + position.coords.latitude + "&lon=" + position.coords.longitude;
    }
    function showError(error) { alert("Erreur de géolocalisation."); }
    function openAvisModal(id, nom) {
        document.getElementById('modalRestoId').value = id;
        document.getElementById('modalRestoName').innerText = "Noter : " + nom;
        document.getElementById('modalAvis').style.display = 'flex';
    }
    function closeAvisModal() { document.getElementById('modalAvis').style.display = 'none'; }
    window.onclick = function(event) {
        var modal = document.getElementById('modalAvis');
        if (event.target == modal) { modal.style.display = "none"; }
    }
</script>

</body>
</html>