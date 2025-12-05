<?php
// Contrôleur utilisé : commande.php
// Informations transmises (Vue -> Contrôleur via GET/POST) :
// - use_remise (GET) : ID de la remise que le client souhaite appliquer.
// - commande_id, restaurant_id, client_id, total, cout_points (POST vers confirmer_commande.php) : Données pour valider le paiement.
// - commande_id (POST vers annuler_commande.php) : Données pour annuler le panier.

// Informations importées (Contrôleur -> Vue) :
// - historiqueCommandes (Tableau principal) contenant pour chaque commande :
//      - commande_id, date_commande, heure_retrait, nom_restaurant
//      - prix_total_remise (Prix initial)
//      - prix_final_a_payer (Prix après réduction)
//      - montant_reduction (Montant en € de la remise)
//      - cout_points_remise (Points à déduire du solde)
//      - solde_points_actuel (Solde fidélité du client)
//      - points_gagnes_commande (Points que la commande va rapporter)
//      - remises_possibles (Tableau des récompenses débloquées)
//      - liste_articles (Tableau des items à la carte)
//      - liste_formules (Tableau structuré des formules avec leur composition)
// - is_guest (Booléen) : Indique si l'utilisateur est en mode invité (impacte l'affichage fidélité).
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier</title>
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

        .commande-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            border: 1px solid rgba(0, 0, 0, 0.02);
        }

        .commande-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f1f1f1;
            padding-bottom: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .resto-name {
            color: var(--accent-color);
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .commande-header h2 {
            margin: 0;
            font-size: 1.4rem;
            color: var(--primary-color);
        }

        .commande-meta {
            font-size: 0.9rem;
            color: var(--text-muted);
            background: #f8f9fa;
            padding: 6px 14px;
            border-radius: 20px;
        }

        .loyalty-section {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }

        .loyalty-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .loyalty-info h4 {
            margin: 0;
            color: #15803d;
            font-size: 1.1rem;
        }

        .loyalty-info p {
            margin: 5px 0 0 0;
            color: #166534;
            font-size: 0.9rem;
        }

        .loyalty-badge {
            background-color: #16a34a;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1rem;
        }

        .loyalty-section.loyalty-guest {
            background-color: #fff7ed;
            border-color: #fed7aa;
        }

        .loyalty-section.loyalty-guest h4 {
            color: #ea580c;
        }

        .loyalty-section.loyalty-guest p {
            color: #c2410c;
        }

        .loyalty-section.loyalty-guest .loyalty-badge {
            background-color: #f97316;
            opacity: 0.9;
        }

        .loyalty-section.loyalty-guest a {
            color: #ea580c;
            text-decoration: underline;
            font-weight: 700;
        }

        .rewards-list {
            border-top: 1px solid #bbf7d0;
            padding-top: 15px;
            margin-top: 10px;
        }

        .rewards-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #15803d;
            text-transform: uppercase;
            margin-bottom: 10px;
            display: block;
        }

        .reward-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 8px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .reward-desc {
            font-size: 0.9rem;
            color: #2c3e50;
            font-weight: 500;
        }

        .btn-use-reward {
            background-color: #16a34a;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-use-reward:hover {
            background-color: #15803d;
        }

        h3 {
            font-size: 1.1rem;
            color: var(--text-main);
            margin-top: 25px;
            margin-bottom: 15px;
            border-left: 4px solid;
            padding-left: 10px;
        }

        h3.titre-formule {
            border-color: #f59e0b;
            color: #d97706;
        }

        h3.titre-article {
            border-color: #3b82f6;
            color: #2563eb;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .modern-table th {
            text-align: left;
            padding: 12px;
            font-size: 0.85rem;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 2px solid #f1f1f1;
        }

        .modern-table td {
            padding: 12px;
            border-bottom: 1px solid #f1f1f1;
            vertical-align: top;
        }

        .col-right {
            text-align: right;
        }

        .col-center {
            text-align: center;
        }

        .font-bold {
            font-weight: 600;
        }

        .composition-tag {
            display: inline-block;
            font-size: 0.8rem;
            color: var(--text-muted);
            background-color: #f1f1f1;
            padding: 2px 8px;
            border-radius: 4px;
            margin-top: 4px;
        }

        .total-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px dashed #e5e7eb;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex-direction: column;
            gap: 5px;
        }

        .total-row {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: 100%;
        }

        .total-label {
            font-size: 1.1rem;
            color: #6b7280;
            margin-right: 15px;
        }

        .total-price {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--success-color);
        }

        .old-price {
            text-decoration: line-through;
            color: #9ca3af;
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .discount-tag {
            color: #e67e22;
            font-weight: 600;
            font-size: 0.9rem;
            margin-right: 15px;
            background: #fff7ed;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .actions-group {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .btn-annuler {
            background-color: white;
            color: var(--danger-color);
            border: 1px solid #fee2e2;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-annuler:hover {
            background-color: #fef2f2;
            border-color: #fca5a5;
        }

        .btn-confirmer {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(39, 174, 96, 0.2);
        }

        .btn-confirmer:hover {
            background-color: #219150;
            transform: translateY(-2px);
        }

        .alert-empty {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            color: var(--text-muted);
        }

        .btn-retour-simple {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
        }

        .btn-retour-simple:hover {
            color: var(--primary-color);
        }

        .pickup-block {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            background: #f0f9ff;
            border: 1px solid #dbeafe;
        }

        .pickup-block h4 {
            margin: 0 0 10px 0;
            font-size: 1rem;
            color: #0f172a;
        }

        .pickup-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .pickup-option {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #1f2937;
        }

        .pickup-option input[type="datetime-local"] {
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.95rem;
        }

        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
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
                    <a href="commande.php?client_id=<?= $_SESSION['client_id'] ?>" class="active">Panier</a>
                    <a href="suivi.php">Suivi</a>
                    <?php if (!isset($_SESSION['is_guest']) || !$_SESSION['is_guest']): ?>
                        <a href="historique.php">Historique</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn-logout">Se déconnecter</a>
                </div>
            <?php else: ?>
                <div class="user-info">UberMiam 🍔</div>
                <div class="header-actions"><a href="login.php">Se connecter</a></div>
            <?php endif; ?>
        </div>

        <h2 class="section-title">🛒 Valider mon panier</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert-error">
                <?php
                $msg = 'Choisissez une heure de livraison valide.';
                if ($_GET['error'] === 'heure_hors_plage') {
                    // Compute the effective max time (5h or restaurant closing) to display a precise message
                    $maxLabel = 'les 5 prochaines heures';
                    if (!empty($historiqueCommandes)) {
                        $commandeTmp = $historiqueCommandes[0];
                        $nowTmp = new DateTime();
                        $maxTmp = (clone $nowTmp)->modify('+5 hours');
                        if (!empty($commandeTmp['restaurant_closing_time'])) {
                            $closingTmp = new DateTime($nowTmp->format('Y-m-d') . ' ' . $commandeTmp['restaurant_closing_time']);
                            if ($closingTmp < $nowTmp) {
                                $closingTmp->modify('+1 day');
                            }
                            if ($closingTmp < $maxTmp) {
                                $maxTmp = $closingTmp;
                            }
                        }
                        $maxLabel = ' ' . $maxTmp->format('H:i');
                    }
                    $msg = 'L\'heure doit être comprise entre maintenant +30 min et au plus tard ' . $maxLabel . '.';
                }
                echo htmlspecialchars($msg);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['is_guest']) && $_SESSION['is_guest']): ?>
            <div
                style="background-color:#fff3cd; color:#856404; padding:15px; border-radius:12px; margin-bottom:30px; border:1px solid #ffeeba;">
                <strong>ℹ️ Mode Invité :</strong> Ce panier est temporaire. Une fois la commande validée, vous devrez créer
                un compte pour la retrouver dans votre historique.
            </div>
        <?php endif; ?>

        <?php
        if (!empty($historiqueCommandes)) {

            foreach ($historiqueCommandes as $commande) {

                $commande_id = $commande['commande_id'];
                $dateCmd = new DateTime($commande['date_commande']);
                $dateAffichee = $dateCmd->format('d/m/Y à H:i');

                $is_guest = isset($_SESSION['is_guest']) && $_SESSION['is_guest'] === true;

                // 1. prix
                $prix_initial = $commande['prix_total_remise'] ?? 0;
                $prix_final = isset($commande['prix_final_a_payer']) ? $commande['prix_final_a_payer'] : $prix_initial;
                $montant_reduction = isset($commande['montant_reduction']) ? $commande['montant_reduction'] : 0;

                // 2. cout en points (si remise utilisée)
                $cout_points = isset($commande['cout_points_remise']) ? intval($commande['cout_points_remise']) : 0;

                // 3. solde actuel
                $solde_actuel = isset($commande['solde_points_actuel']) ? intval($commande['solde_points_actuel']) : 0;

                // 4. points gagnés (sur le prix payé)
                // si invité, on gagne 0
                $points_gagnes = $is_guest ? 0 : intval(floor($prix_final));
                $points_potentiels = intval(floor($prix_final)); // pour l'affichage invité
        
                // 5. calcul du solde final projeté
                // solde final = solde actuel - cout recompense + points gagnés
                $solde_final_projete = $solde_actuel - $cout_points + $points_gagnes;
                // ---------------------------
        
                echo "<div class='commande-card'>";

                echo "<div class='commande-header'>";
                echo "<div>";
                if (isset($commande['nom_restaurant'])) {
                    echo "<div class='resto-name'>" . htmlspecialchars($commande['nom_restaurant']) . "</div>";
                }
                echo "<h2>Commande #" . htmlspecialchars($commande_id) . "</h2>";
                echo "</div>";
                echo "<div class='commande-meta'>";
                echo "📅 " . $dateAffichee;
                if (!empty($commande['heure_retrait'])) {
                    $est_asap = isset($commande['est_asap']) ? $commande['est_asap'] : false;
                    if ($est_asap) {
                        echo " &bull; ⏱️ Dès que possible";
                    } else {
                        $retraitCmd = new DateTime($commande['heure_retrait']);
                        echo " &bull; ⏱️ " . $retraitCmd->format('H:i');
                    }
                }
                echo "</div>";
                echo "</div>";

                echo "<div class='commande-body'>";

                // formules
                if (!empty($commande['liste_formules'])) {
                    echo "<h3 class='titre-formule'>🍱 Formules</h3>";
                    echo "<table class='modern-table'><thead><tr><th>Menu</th><th>Composition</th><th class='col-right'>Prix</th></tr></thead><tbody>";
                    foreach ($commande['liste_formules'] as $formule) {
                        echo "<tr>";
                        echo "<td class='font-bold'>" . htmlspecialchars($formule['nom']) . "</td>";
                        echo "<td>";
                        if (!empty($formule['items'])) {
                            echo "<span class='composition-tag'>" . htmlspecialchars(implode(' + ', $formule['items'])) . "</span>";
                        }
                        echo "</td>";
                        echo "<td class='col-right font-bold'>" . number_format($formule['prix'], 2, ',', ' ') . " €</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                }

                // articles
                if (!empty($commande['liste_articles'])) {
                    echo "<h3 class='titre-article'>📦 Articles à la carte</h3>";
                    echo "<table class='modern-table'><thead><tr><th>Article</th><th class='col-right'>P.U.</th><th class='col-center'>Qté</th><th class='col-right'>Total</th></tr></thead><tbody>";
                    foreach ($commande['liste_articles'] as $item) {
                        $sousTotal = $item['prix'] * $item['quantite'];
                        echo "<tr>";
                        echo "<td class='font-bold'>" . htmlspecialchars($item['nom']) . "</td>";
                        echo "<td class='col-right'>" . number_format($item['prix'], 2, ',', ' ') . " €</td>";
                        echo "<td class='col-center'>x" . $item['quantite'] . "</td>";
                        echo "<td class='col-right font-bold'>" . number_format($sousTotal, 2, ',', ' ') . " €</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                }

                // fidélité et remises
                if (!$is_guest) {
                    echo "<div class='loyalty-section'>";

                    echo "<div class='loyalty-header'>";
                    echo "<div class='loyalty-info'>";
                    echo "<h4>🎁 Programme Fidélité</h4>";
                    echo "<p>Solde actuel : <strong>{$solde_actuel} pts</strong></p>";

                    if ($cout_points > 0) {
                        echo "<p style='color:#e74c3c; font-size:0.9rem;'>Coût récompense : -{$cout_points} pts</p>";
                    }

                    echo "<p style='font-size:0.85em; color:#666;'>Nouveau solde après validation : <strong>{$solde_final_projete} pts</strong></p>";
                    echo "</div>";
                    echo "<div class='loyalty-badge'>+{$points_gagnes} pts</div>";
                    echo "</div>";

                    // liste des récompenses dispo (si aucune sélectionnée)
                    if ($montant_reduction == 0 && !empty($commande['remises_possibles'])) {
                        echo "<div class='rewards-list'>";
                        echo "<span class='rewards-title'>✨ Récompenses disponibles :</span>";

                        foreach ($commande['remises_possibles'] as $remise) {
                            $link = "commande.php?use_remise=" . $remise['remise_id'];
                            echo "<div class='reward-item'>";
                            echo "<span class='reward-desc'>" . htmlspecialchars($remise['description']) . "</span>";
                            echo "<a href='$link' class='btn-use-reward'>Utiliser (-{$remise['seuil_points']} pts)</a>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }

                    // remise appliquée
                    if ($montant_reduction > 0) {
                        echo "<div style='margin-top:15px; padding:10px; background:#fff3cd; border:1px solid #ffeeba; border-radius:8px; color:#856404;'>";
                        echo "<strong>✅ Récompense active :</strong> -" . number_format($montant_reduction, 2) . " €";
                        echo " <a href='commande.php' style='float:right; color:#856404; text-decoration:underline;'>Annuler</a>";
                        echo "</div>";
                    }

                    echo "</div>";
                } else {
                    echo "<div class='loyalty-section loyalty-guest'>";
                    echo "<div class='loyalty-header'>";
                    echo "<div class='loyalty-info'>";
                    echo "<h4>🌟 Ne passez pas à côté !</h4>";
                    echo "<p>Cette commande pourrait vous rapporter <strong>{$points_potentiels} points</strong>.</p>";
                    echo "<a href='create_account.php'>Créez un compte pour en profiter</a>";
                    echo "</div>";
                    echo "<div class='loyalty-badge'>0 pts</div>";
                    echo "</div>";
                    echo "</div>";
                }

                // total recalculé
                echo "<div class='total-section'>";
                echo "<div class='total-row'>";
                if ($montant_reduction > 0) {
                    echo "<span class='discount-tag'>Remise - " . number_format($montant_reduction, 2) . " €</span>";
                    echo "<span class='old-price'>" . number_format($prix_initial, 2, ',', ' ') . " €</span>";
                } else {
                    echo "<span class='total-label'>Total à payer :</span>";
                }
                // prix final
                echo "<span class='total-price'>" . number_format($prix_final, 2, ',', ' ') . " €</span>";
                echo "</div>";
                echo "</div>";

                // actions
                echo "<div class='actions-group'>";

                // annulation
                echo "<form action='annuler_commande.php' method='POST' onsubmit=\"return confirm('Êtes-vous sûr de vouloir vider votre panier ?');\">";
                echo "<input type='hidden' name='commande_id' value='" . $commande_id . "'>";
                echo "<button type='submit' class='btn-annuler'>🗑️ Annuler</button>";
                echo "</form>";
                // confirmation
                echo "<form action='confirmer_commande.php' method='POST' onsubmit=\"return confirm('Êtes-vous sûr de vouloir valider et payer cette commande ?');\">";
                echo "<input type='hidden' name='commande_id' value='" . $commande_id . "'>";
                $resto_id = isset($commande['restaurant_id']) ? $commande['restaurant_id'] : 0;
                $client_id = isset($_SESSION['client_id']) ? $_SESSION['client_id'] : 0;

                echo "<input type='hidden' name='restaurant_id' value='" . $resto_id . "'>";
                echo "<input type='hidden' name='client_id' value='" . $client_id . "'>";
                echo "<input type='hidden' name='total' value='" . $prix_final . "'>";
                echo "<input type='hidden' name='cout_points' value='" . $cout_points . "'>";

                $nowPicker = new DateTime();
                $defaultRetrait = (clone $nowPicker)->modify('+30 minutes');
                $maxRetrait = (clone $nowPicker)->modify('+5 hours');

                if (!empty($commande['restaurant_closing_time'])) {
                    $closingTime = new DateTime($nowPicker->format('Y-m-d') . ' ' . $commande['restaurant_closing_time']);
                    if ($closingTime < $nowPicker) {
                        $closingTime->modify('+1 day');
                    }
                    if ($closingTime < $maxRetrait) {
                        $maxRetrait = $closingTime;
                    }
                }

                $minTimeAttr = $defaultRetrait->format('H:i');
                $defaultTimeAttr = $defaultRetrait->format('H:i');
                $maxTimeAttr = $maxRetrait->format('H:i');

                echo "<div class='pickup-block'>";
                echo "<h4>⏱️ Choisir une heure de livraison</h4>";
                echo "<div class='pickup-options'>";
                echo "<label class='pickup-option'><input type='radio' name='retrait_option' value='asap' checked> Dès que possible</label>";
                echo "<label class='pickup-option'><input type='radio' name='retrait_option' value='custom'> Choisir une heure (dans les 5h)<input type='time' name='heure_retrait' value='{$defaultTimeAttr}' min='{$minTimeAttr}' max='{$maxTimeAttr}' oninvalid=\"this.setCustomValidity('Veuillez sélectionner une heure comprise entre ' + this.min + ' et ' + this.max)\" oninput=\"this.setCustomValidity('')\"></label>";
                echo "</div>";
                echo "<p style='margin:8px 0 0 0; color:#475569; font-size:0.9rem;'>Par défaut : +30 min. Intervalle autorisée : jusqu'à 5h ou fermeture du restaurant.</p>";
                echo "</div>";

                echo "<button type='submit' class='btn-confirmer'>✅ Valider et Payer</button>";
                echo "</form>";

                echo "</div>";

                echo "</div>";
                echo "</div>";
            }

        } else {
            echo "<div class='alert-empty'>";
            echo "<h3>Votre panier est vide 🛒</h3>";
            echo "<p>Vous n'avez pas encore sélectionné d'articles.</p>";
            echo "<a href='index.php' class='btn-confirmer' style='text-decoration:none; display:inline-block; margin-top:15px;'>Choisir un restaurant</a>";
            echo "</div>";
        }
        ?>

        <a href="index.php" class="btn-retour-simple">← Retour aux restaurants</a>

    </div>

</body>

</html>