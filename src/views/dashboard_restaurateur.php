<?php
// Contrôleur utilisé : restaurateur_space.php
// Informations transmises (via POST) :
// - Action : add_item (nom, prix, categorie_id, disponible, complements[]), 
//            add_formule (nom, prix, categories[], cond_jour[], cond_debut[], cond_fin[]), 
//            add_horaire (jour, debut, fin)

// Informations transmises (via GET) :
// - Action : del_horaire (id)
// - Page : stats, add_item, formules, horaires (pour la navigation)

// Informations importées :
// - message_succes, message_erreur
// - stats (tableau des ventes)
// - categories_items (liste des catégories pour les formulaires)
// - liste_horaires (tableau des créneaux d'ouverture)
// - jours_semaine (mapping ID jour -> Nom jour)
// - conditions_dispo (liste des conditions existantes pour les formules)
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Restaurateur - <?= htmlspecialchars($restaurant_nom) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #2c3e50;
            --sidebar-text: #ecf0f1;
            --accent-color: #3498db;
            --bg-body: #f4f6f9;
            --card-white: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            display: flex;
            min-height: 100vh;
            background-color: var(--bg-body);
        }

        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: fixed;
            height: 100%;
        }

        .sidebar h2 {
            font-size: 1.2rem;
            margin-bottom: 30px;
            border-bottom: 1px solid #34495e;
            padding-bottom: 15px;
        }

        .nav-link {
            text-decoration: none;
            color: #bdc3c7;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 6px;
            transition: all 0.2s;
            display: block;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: var(--accent-color);
            color: white;
        }

        .logout-btn {
            margin-top: auto;
            color: #e74c3c;
            border: 1px solid #e74c3c;
            text-align: center;
        }

        .logout-btn:hover {
            background: #e74c3c;
            color: white;
        }

        .main-content {
            margin-left: 290px;
            padding: 40px;
            width: 100%;
        }

        .page-title {
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .card {
            background: var(--card-white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Formulaire */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .ingredient-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: flex-end;
            margin-bottom: 10px;
            background: #f9f9f9;
            padding: 10px;
            border: 1px dashed #ddd;
            border-radius: 6px;
        }

        .ingredient-row input {
            flex: 1;
            min-width: 120px;
        }

        .ingredient-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eef6ff;
            color: #1b4f72;
            padding: 6px 10px;
            border-radius: 14px;
            font-size: 0.9em;
        }

        .ingredient-panel {
            background: #f7faff;
            border: 1px solid #dfe7f3;
            padding: 12px;
            border-radius: 8px;
            margin-top: 10px;
        }

        button.btn-submit {
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        button.btn-submit:hover {
            background-color: #2980b9;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            text-align: left;
            background: #f8f9fa;
            padding: 12px;
            border-bottom: 2px solid #ddd;
        }

        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .tag {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85em;
            font-weight: bold;
        }

        .tag-plat {
            background: #e3f2fd;
            color: #1976d2;
        }

        .tag-menu {
            background: #fff3e0;
            color: #f57c00;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>👨‍🍳 Espace Pro<br><small
                style="font-size:0.7em; color:#bdc3c7"><?= htmlspecialchars($restaurant_nom) ?></small></h2>

        <a href="espace_restaurateur.php?page=stats" class="nav-link <?= $page == 'stats' ? 'active' : '' ?>">
            📊 Statistiques
        </a>
        <a href="espace_restaurateur.php?page=add_item" class="nav-link <?= $page == 'add_item' ? 'active' : '' ?>">
            🍽️ Gérer les plats
        </a>
        <a href="restaurateur_space.php?page=formules" class="nav-link <?= $page == 'formules' ? 'active' : '' ?>">
            🍱 Gérer les formules
        </a>
        <a href="restaurateur_space.php?page=horaires" class="nav-link <?= $page == 'horaires' ? 'active' : '' ?>">
            🕒 Horaires d'ouverture
        </a>

        <a href="logout.php" class="nav-link logout-btn">Déconnexion</a>
    </div>

    <div class="main-content">

        <?php if ($message_succes): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message_succes) ?></div>
        <?php endif; ?>
        <?php if ($message_erreur): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message_erreur) ?></div>
        <?php endif; ?>


        <?php if ($page === 'stats'): ?>
            <h1 class="page-title">Performances des ventes (12 mois)</h1>

            <div class="card">
                <?php if (empty($stats)): ?>
                    <p style="color:#777;">Aucune vente enregistrée pour le moment.</p>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Période</th>
                                <th>Plat / Item</th>
                                <th>Ventes Totales</th>
                                <th>Répartition</th>
                                <th>Revenu Théorique</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats as $row): ?>
                                <tr>
                                    <td style="font-weight:bold; color:#555;">
                                        <?= str_pad($row['mois'], 2, '0', STR_PAD_LEFT) . '/' . $row['annee'] ?>
                                    </td>

                                    <td><?= htmlspecialchars($row['nom']) ?></td>

                                    <td style="font-size:1.1em;"><strong><?= $row['nb_total_ventes'] ?></strong></td>

                                    <td>
                                        <?php if ($row['dont_x_plat'] > 0): ?>
                                            <span class="tag tag-plat"><?= $row['dont_x_plat'] ?> carte</span>
                                        <?php endif; ?>
                                        <?php if ($row['dont_x_menu'] > 0): ?>
                                            <span class="tag tag-menu"><?= $row['dont_x_menu'] ?> menu</span>
                                        <?php endif; ?>
                                    </td>

                                    <td style="color:#27ae60; font-weight:bold;">
                                        <?= number_format($row['revenu_theorique'], 2, ',', ' ') ?> €
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>


        <?php if ($page === 'add_item'): ?>
            <h1 class="page-title">🍽️ Gérer les plats</h1>

            <div class="card" style="max-width: 600px;">
                <h2 style="margin-top:0;">Ajouter un nouveau plat</h2>
                <form method="POST" action="espace_restaurateur.php?page=add_item">
                    <input type="hidden" name="action" value="add_item">

                    <div class="form-group">
                        <label for="nom">Nom du plat :</label>
                        <input type="text" id="nom" name="nom" placeholder="Ex: Pizza 4 Fromages" required>
                    </div>

                    <div class="form-group">
                        <label for="prix">Prix (€) :</label>
                        <input type="number" id="prix" name="prix" step="0.10" min="0" placeholder="Ex: 12.50" required>
                    </div>

                    <div class="form-group">
                        <label for="cat">Catégorie :</label>
                        <select id="cat" name="categorie_id" required>
                            <?php foreach ($categories_items as $cat): ?>
                                <option value="<?= $cat['categorie_item_id'] ?>">
                                    <?= htmlspecialchars($cat['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Ingrédients du plat :</label>
                        <p style="color:#777; margin-top:5px;">Ajoutez les ingrédients (création à la volée) avec leur
                            quantité pour ce plat.</p>

                        <div id="ingredient-rows">
                            <div class="ingredient-row">
                                <div style="flex:1; min-width:160px;">
                                    <label style="font-weight:500;">Nom</label>
                                    <input type="text" name="ingredient_nom[]" placeholder="Ex: Tomate" />
                                </div>
                                <div style="width:140px;">
                                    <label style="font-weight:500;">kcal / 100g</label>
                                    <input type="number" name="ingredient_kcal[]" min="0" placeholder="18">
                                </div>
                                <div style="width:160px;">
                                    <label style="font-weight:500;">Protéines / 100g</label>
                                    <input type="number" step="0.1" name="ingredient_proteines[]" min="0" placeholder="0.9">
                                </div>
                                <div style="width:140px;">
                                    <label style="font-weight:500;">Quantité (g)</label>
                                    <input type="number" name="ingredient_quantite[]" min="1" placeholder="150">
                                </div>
                                <button type="button" onclick="removeIngredientRow(this)"
                                    style="background:#ffecec; color:#c0392b; border:1px solid #e5b9b9; padding:8px 10px; border-radius:6px; cursor:pointer;">Supprimer</button>
                            </div>
                        </div>

                        <div class="ingredient-actions" style="margin-top:10px;">
                            <button type="button" onclick="addIngredientRow()"
                                style="background:#e9f5ff; color:#2980b9; border:1px solid #b7d7f2; padding:8px 10px; border-radius:6px; cursor:pointer;">+
                                Ajouter un ingrédient</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Compléments (Sauces, etc.) :</label>

                        <div style="position:relative;">
                            <input type="text" id="search-complement" placeholder="Tapez pour chercher un item..."
                                autocomplete="off">
                            <div id="search-results"
                                style="position:absolute; width:100%; background:white; border:1px solid #ddd; border-top:none; z-index:100; max-height:200px; overflow-y:auto; display:none;">
                            </div>
                        </div>

                        <div id="selected-complements" style="margin-top:10px; display:flex; flex-wrap:wrap; gap:5px;">
                        </div>

                        <div id="hidden-inputs"></div>
                    </div>

                    <div class="form-group" style="margin-top:20px;">
                        <label style="display:inline-flex; align-items:center; cursor:pointer;">
                            <input type="checkbox" name="disponible" checked style="width:auto; margin-right:10px;">
                            Rendre ce produit disponible immédiatement à la vente
                        </label>
                    </div>

                    <button type="submit" class="btn-submit">Enregistrer le plat</button>
                </form>
            </div>

            <div class="card" style="margin-top:30px;">
                <h2 style="margin-top:0;">Plats existants</h2>
                <?php if (!empty($items_owner)): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Prix (€)</th>
                                <th>Disponible</th>
                                <th>Ingrédients</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items_owner as $it): ?>
                                <tr>
                                    <td><?= htmlspecialchars($it['nom']) ?></td>
                                    <td><?= htmlspecialchars($it['nom_categorie']) ?></td>
                                    <td><?= number_format($it['prix'], 2, ',', ' ') ?></td>
                                    <td>
                                        <form method="POST" style="display:inline; margin:0;">
                                            <input type="hidden" name="action" value="toggle_disponible">
                                            <input type="hidden" name="item_id" value="<?= $it['item_id'] ?>">
                                            <button type="submit"
                                                style="background:none; border:none; padding:0; cursor:pointer; font-size:1.2em;">
                                                <?= (isset($it['est_disponible']) && ($it['est_disponible'] === 't' || $it['est_disponible'] === true || $it['est_disponible'] === 'true')) ? '✅' : '❌' ?>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:10px;">
                                            <span class="ingredient-pill">
                                                <?= isset($it['ingredients']) ? count($it['ingredients']) : 0 ?> ingrédient(s)
                                            </span>
                                            <button type="button" onclick="toggleIngredients(<?= $it['item_id'] ?>)"
                                                style="background:#eef6ff; color:#1b4f72; border:1px solid #c6d8ee; padding:6px 10px; border-radius:6px; cursor:pointer;">Gérer</button>
                                        </div>
                                    </td>
                                    <td>
                                        <form method="POST" style="display:inline; margin:0;">
                                            <input type="hidden" name="action" value="delete_item">
                                            <input type="hidden" name="item_id" value="<?= $it['item_id'] ?>">
                                            <button type="submit" onclick="return confirm('Supprimer ce plat ?')"
                                                style="background:#e74c3c; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                                <tr id="ingredients-row-<?= $it['item_id'] ?>" style="display:none; background:#fafbff;">
                                    <td colspan="6">
                                        <div class="ingredient-panel">
                                            <h4 style="margin-top:0;">Ingrédients pour "<?= htmlspecialchars($it['nom']) ?>"</h4>

                                            <?php if (!empty($it['ingredients'])): ?>
                                                <ul style="padding-left:18px; color:#34495e;">
                                                    <?php foreach ($it['ingredients'] as $ing): ?>
                                                        <li style="margin-bottom:6px;">
                                                            <strong><?= htmlspecialchars($ing['nom']) ?></strong>
                                                            - <?= intval($ing['quantite_g']) ?> g
                                                            (<?= intval($ing['kcal_pour_100g']) ?> kcal/100g,
                                                            <?= floatval($ing['proteines_pour_100g']) ?> g prot/100g)
                                                            <form method="POST" style="display:inline; margin-left:10px;">
                                                                <input type="hidden" name="action" value="remove_ingredient_from_item">
                                                                <input type="hidden" name="item_id" value="<?= $it['item_id'] ?>">
                                                                <input type="hidden" name="ingredient_id"
                                                                    value="<?= $ing['ingredient_id'] ?>">
                                                                <button type="submit"
                                                                    style="background:#ffecec; color:#c0392b; border:1px solid #e5b9b9; padding:4px 8px; border-radius:6px; cursor:pointer;">Retirer</button>
                                                            </form>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <p style="color:#777;">Aucun ingrédient pour le moment.</p>
                                            <?php endif; ?>

                                            <div style="margin-top:10px;">
                                                <form method="POST"
                                                    style="display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end;">
                                                    <input type="hidden" name="action" value="add_ingredient_to_item">
                                                    <input type="hidden" name="item_id" value="<?= $it['item_id'] ?>">

                                                    <div style="flex:1; min-width:150px;">
                                                        <label style="font-weight:500;">Nom</label>
                                                        <input type="text" name="ingredient_nom" placeholder="Ex: Champignon"
                                                            required>
                                                    </div>
                                                    <div style="width:120px;">
                                                        <label style="font-weight:500;">kcal / 100g</label>
                                                        <input type="number" name="ingredient_kcal" min="0" value="0">
                                                    </div>
                                                    <div style="width:150px;">
                                                        <label style="font-weight:500;">Protéines / 100g</label>
                                                        <input type="number" step="0.1" name="ingredient_proteines" min="0"
                                                            value="0">
                                                    </div>
                                                    <div style="width:120px;">
                                                        <label style="font-weight:500;">Quantité (g)</label>
                                                        <input type="number" name="ingredient_quantite" min="1" value="1">
                                                    </div>

                                                    <button type="submit"
                                                        style="background:#e9f5ff; color:#1b4f72; border:1px solid #c6d8ee; padding:8px 12px; border-radius:6px; cursor:pointer;">+
                                                        Ajouter</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color:#777;">Aucun plat enregistré pour le moment.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if ($page === 'formules'): ?>
            <h1 class="page-title">🍱 Gérer les formules</h1>

            <div class="card" style="max-width: 600px;">
                <h2 style="margin-top:0;">Ajouter une nouvelle formule</h2>
                <form method="POST" action="espace_restaurateur.php?page=formules">
                    <input type="hidden" name="action" value="add_formule">

                    <div class="form-group">
                        <label for="nom_f">Nom de la formule :</label>
                        <input type="text" id="nom_f" name="nom" placeholder="Ex: Menu Midi (Entrée + Plat)" required>
                    </div>

                    <div class="form-group">
                        <label for="prix_f">Prix global (€) :</label>
                        <input type="number" id="prix_f" name="prix" step="0.10" min="0" placeholder="Ex: 18.00" required>
                    </div>

                    <div class="form-group">
                        <label>Composition (Cochez les étapes) :</label>
                        <div
                            style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background:#f9f9f9; padding:15px; border-radius:5px; border:1px solid #ddd;">

                            <?php foreach ($categories_items as $cat): ?>
                                <label style="display:flex; align-items:center; cursor:pointer; font-weight:normal;">
                                    <input type="checkbox" name="categories[]" value="<?= $cat['categorie_item_id'] ?>"
                                        style="width:auto; margin-right:8px;">
                                    <?= htmlspecialchars($cat['nom']) ?>
                                </label>
                            <?php endforeach; ?>

                        </div>
                        <small style="color:#777; display:block; margin-top:5px;">L'ordre de sélection déterminera l'ordre
                            des étapes pour le client.</small>
                    </div>

                    <div class="form-group">
                        <label>Disponibilité (Optionnel) :</label>
                        <small style="color:#777;">Ajoutez les créneaux où cette formule est active.</small>

                        <table id="table-conditions" style="width:100%; margin-top:5px; border-collapse:collapse;">
                        </table>

                        <button type="button" onclick="ajouterLigneCondition()"
                            style="background:#eee; border:1px solid #ddd; padding:5px 10px; margin-top:5px; cursor:pointer; border-radius:4px;">
                            + Ajouter un créneau
                        </button>
                    </div>

                    <script>
                        const jours = { 1: 'Lundi', 2: 'Mardi', 3: 'Mercredi', 4: 'Jeudi', 5: 'Vendredi', 6: 'Samedi', 7: 'Dimanche' };

                        function ajouterLigneCondition() {
                            const table = document.getElementById('table-conditions');
                            const row = table.insertRow();

                            // jour
                            const cell1 = row.insertCell(0);
                            let selectHtml = '<select name="cond_jour[]" style="padding:5px;">';
                            for (const [k, v] of Object.entries(jours)) {
                                selectHtml += `<option value="${k}">${v}</option>`;
                            }
                            selectHtml += '</select>';
                            cell1.innerHTML = selectHtml;

                            // debut
                            const cell2 = row.insertCell(1);
                            cell2.innerHTML = '<input type="time" name="cond_debut[]" required style="padding:5px;">';

                            // fin
                            const cell3 = row.insertCell(2);
                            cell3.innerHTML = '<input type="time" name="cond_fin[]" required style="padding:5px;">';

                            // suppression
                            const cell4 = row.insertCell(3);
                            cell4.innerHTML = '<button type="button" onclick="this.parentElement.parentElement.remove()" style="color:red; border:none; background:none; cursor:pointer;">&times;</button>';
                        }
                    </script>

                    <button type="submit" class="btn-submit">Créer la formule</button>
                </form>
            </div>

            <div class="card" style="margin-top:30px;">
                <h2 style="margin-top:0;">Formules existantes</h2>
                <?php if (!empty($formules_owner)): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prix (€)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($formules_owner as $f): ?>
                                <tr>
                                    <td><?= htmlspecialchars($f['nom']) ?></td>
                                    <td><?= number_format($f['prix'], 2, ',', ' ') ?></td>
                                    <td>
                                        <form method="POST" style="display:inline; margin:0;">
                                            <input type="hidden" name="action" value="delete_formule">
                                            <input type="hidden" name="formule_id" value="<?= $f['formule_id'] ?>">
                                            <button type="submit" onclick="return confirm('Supprimer cette formule ?')"
                                                style="background:#e74c3c; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">🗑️
                                                Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color:#777;">Aucune formule enregistrée pour le moment.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($page === 'horaires'): ?>
            <h1 class="page-title">Mes Horaires d'ouverture</h1>

            <div style="display:flex; gap:20px; flex-wrap:wrap;">

                <div class="card" style="flex:1; min-width:300px;">
                    <h3>Semaine Type</h3>
                    <?php if (empty($liste_horaires)): ?>
                        <p>Aucun horaire défini. Le restaurant apparaît comme fermé.</p>
                    <?php else: ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Jour</th>
                                    <th>Ouverture</th>
                                    <th>Fermeture</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($liste_horaires as $h): ?>
                                    <tr>
                                        <td><strong><?= $jours_semaine[$h['jour_semaine']] ?></strong></td>
                                        <td><?= substr($h['heure_ouverture'], 0, 5) ?></td>
                                        <td><?= substr($h['heure_fermeture'], 0, 5) ?></td>
                                        <td>
                                            <a href="espace_restaurateur.php?page=horaires&action=del_horaire&id=<?= $h['horaire_ouverture_id'] ?>"
                                                style="color:#e74c3c; text-decoration:none; font-weight:bold;"
                                                onclick="return confirm('Supprimer ce créneau ?');">
                                                🗑️
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <div class="card" style="flex:1; min-width:300px; height:fit-content;">
                    <h3>Ajouter un créneau</h3>
                    <form method="POST" action="restaurateur_space.php?page=horaires">
                        <input type="hidden" name="action" value="add_horaire">

                        <div class="form-group">
                            <label>Jour :</label>
                            <select name="jour" required>
                                <?php foreach ($jours_semaine as $num => $nom): ?>
                                    <option value="<?= $num ?>"><?= $nom ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div style="display:flex; gap:10px;">
                            <div class="form-group" style="flex:1;">
                                <label>De :</label>
                                <input type="time" name="debut" required>
                            </div>
                            <div class="form-group" style="flex:1;">
                                <label>À :</label>
                                <input type="time" name="fin" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit" style="width:100%;">Ajouter</button>
                    </form>
                </div>

            </div>
        <?php endif; ?>

    </div>
    <script>
        function addIngredientRow() {
            const container = document.getElementById('ingredient-rows');
            const row = document.createElement('div');
            row.className = 'ingredient-row';
            row.innerHTML = `
                <div style="flex:1; min-width:160px;">
                    <label style="font-weight:500;">Nom</label>
                    <input type="text" name="ingredient_nom[]" placeholder="Ex: Mozzarella" />
                </div>
                <div style="width:140px;">
                    <label style="font-weight:500;">kcal / 100g</label>
                    <input type="number" name="ingredient_kcal[]" min="0" placeholder="250">
                </div>
                <div style="width:160px;">
                    <label style="font-weight:500;">Protéines / 100g</label>
                    <input type="number" step="0.1" name="ingredient_proteines[]" min="0" placeholder="18">
                </div>
                <div style="width:140px;">
                    <label style="font-weight:500;">Quantité (g)</label>
                    <input type="number" name="ingredient_quantite[]" min="1" placeholder="80">
                </div>
                <button type="button" onclick="removeIngredientRow(this)" style="background:#ffecec; color:#c0392b; border:1px solid #e5b9b9; padding:8px 10px; border-radius:6px; cursor:pointer;">Supprimer</button>
            `;
            container.appendChild(row);
        }

        function removeIngredientRow(btn) {
            const container = document.getElementById('ingredient-rows');
            if (container.children.length > 1) {
                btn.parentElement.remove();
            } else {
                const inputs = btn.parentElement.querySelectorAll('input');
                inputs.forEach(i => i.value = '');
            }
        }

        function toggleIngredients(itemId) {
            const row = document.getElementById(`ingredients-row-${itemId}`);
            if (!row) return;
            row.style.display = row.style.display === 'table-row' ? 'none' : 'table-row';
        }

        const searchInput = document.getElementById('search-complement');
        const resultsDiv = document.getElementById('search-results');
        const selectedDiv = document.getElementById('selected-complements');
        const hiddenDiv = document.getElementById('hidden-inputs');

        if (searchInput) {
            let selectedIds = new Set();

            searchInput.addEventListener('input', function () {
                const term = this.value;
                if (term.length < 2) {
                    resultsDiv.style.display = 'none';
                    return;
                }

                fetch(`api_search_items.php?term=${encodeURIComponent(term)}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsDiv.innerHTML = '';
                        if (data.length > 0) {
                            resultsDiv.style.display = 'block';
                            data.forEach(item => {
                                if (!selectedIds.has(item.item_id)) {
                                    const div = document.createElement('div');
                                    div.style.padding = '8px';
                                    div.style.cursor = 'pointer';
                                    div.style.borderBottom = '1px solid #eee';
                                    div.onmouseover = () => div.style.background = '#f9f9f9';
                                    div.onmouseout = () => div.style.background = 'white';
                                    div.textContent = `${item.nom} (${item.prix}€)`;

                                    div.onclick = () => addItem(item);
                                    resultsDiv.appendChild(div);
                                }
                            });
                        } else {
                            resultsDiv.style.display = 'none';
                        }
                    });
            });

            function addItem(item) {
                selectedIds.add(item.item_id);

                const badge = document.createElement('span');
                badge.style.background = '#e3f2fd';
                badge.style.color = '#1976d2';
                badge.style.padding = '5px 10px';
                badge.style.borderRadius = '15px';
                badge.style.fontSize = '0.9em';
                badge.innerHTML = `${item.nom} <span style="cursor:pointer; font-weight:bold; margin-left:5px;" onclick="removeItem(this, ${item.item_id})">&times;</span>`;
                selectedDiv.appendChild(badge);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'complements[]';
                input.value = item.item_id;
                input.id = `input-comp-${item.item_id}`;
                hiddenDiv.appendChild(input);

                searchInput.value = '';
                resultsDiv.style.display = 'none';
            }

            window.removeItem = function (span, id) {
                selectedIds.delete(id);
                span.parentElement.remove();
                document.getElementById(`input-comp-${id}`).remove();
            };

            document.addEventListener('click', function (e) {
                if (e.target !== searchInput) {
                    resultsDiv.style.display = 'none';
                }
            });
        }
    </script>

</body>

</html>