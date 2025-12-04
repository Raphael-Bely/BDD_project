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
            --accent-color: #3498db; /* Bleu Pro */
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

        /* --- SIDEBAR (Gauche) --- */
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

        .nav-link:hover, .nav-link.active {
            background-color: var(--accent-color);
            color: white;
        }

        .logout-btn {
            margin-top: auto;
            color: #e74c3c;
            border: 1px solid #e74c3c;
            text-align: center;
        }
        .logout-btn:hover { background: #e74c3c; color: white; }

        /* --- MAIN CONTENT (Droite) --- */
        .main-content {
            margin-left: 290px; /* Largeur sidebar + padding */
            padding: 40px;
            width: 100%;
        }

        .page-title {
            color: #2c3e50;
            margin-bottom: 30px;
        }

        /* --- UI ELEMENTS --- */
        .card {
            background: var(--card-white);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        /* Messages */
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Formulaire */
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; }
        input[type="text"], input[type="number"], select {
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;
            font-size: 1rem;
        }
        button.btn-submit {
            background-color: var(--accent-color); color: white; border: none;
            padding: 12px 20px; border-radius: 5px; cursor: pointer; font-size: 1rem;
        }
        button.btn-submit:hover { background-color: #2980b9; }

        /* Tableaux */
        .data-table {
            width: 100%; border-collapse: collapse; margin-top: 10px;
        }
        .data-table th {
            text-align: left; background: #f8f9fa; padding: 12px; border-bottom: 2px solid #ddd;
        }
        .data-table td {
            padding: 12px; border-bottom: 1px solid #eee;
        }
        .tag {
            padding: 4px 8px; border-radius: 4px; font-size: 0.85em; font-weight: bold;
        }
        .tag-plat { background: #e3f2fd; color: #1976d2; }
        .tag-menu { background: #fff3e0; color: #f57c00; }

    </style>
</head>
<body>

    <div class="sidebar">
        <h2>👨‍🍳 Espace Pro<br><small style="font-size:0.7em; color:#bdc3c7"><?= htmlspecialchars($restaurant_nom) ?></small></h2>
        
        <a href="espace_restaurateur.php?page=stats" class="nav-link <?= $page == 'stats' ? 'active' : '' ?>">
            📊 Statistiques
        </a>
        <a href="espace_restaurateur.php?page=add_item" class="nav-link <?= $page == 'add_item' ? 'active' : '' ?>">
            ➕ Ajouter un plat
        </a>
        <a href="espace_restaurateur.php?page=formules" class="nav-link <?= $page == 'formules' ? 'active' : '' ?>">
            🍱 Gérer les formules
        </a>
        <a href="espace_restaurateur.php?page=horaires" class="nav-link <?= $page == 'horaires' ? 'active' : '' ?>">
            🕒 Horaires d'ouverture
        </a>

        <a href="logout.php" class="nav-link logout-btn">Déconnexion</a>
    </div>

    <div class="main-content">

        <?php if ($message_succes): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($message_succes) ?></div>
        <?php endif; ?>
        <?php if ($message_erreur): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($message_erreur) ?></div>
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
                                    <?php if($row['dont_x_plat'] > 0): ?>
                                        <span class="tag tag-plat"><?= $row['dont_x_plat'] ?> carte</span>
                                    <?php endif; ?>
                                    <?php if($row['dont_x_menu'] > 0): ?>
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
            <h1 class="page-title">Ajouter un nouveau produit</h1>
            
            <div class="card" style="max-width: 600px;">
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
                            <?php foreach($categories_items as $cat): ?>
                                <option value="<?= $cat['categorie_item_id'] ?>">
                                    <?= htmlspecialchars($cat['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
        <?php endif; ?>
        <?php if ($page === 'formules'): ?>
            <h1 class="page-title">Créer un nouveau Menu / Formule</h1>
            
            <div class="card" style="max-width: 600px;">
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
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background:#f9f9f9; padding:15px; border-radius:5px; border:1px solid #ddd;">
                            
                            <?php foreach($categories_items as $cat): ?>
                                <label style="display:flex; align-items:center; cursor:pointer; font-weight:normal;">
                                    <input type="checkbox" name="categories[]" value="<?= $cat['categorie_item_id'] ?>" style="width:auto; margin-right:8px;">
                                    <?= htmlspecialchars($cat['nom']) ?>
                                </label>
                            <?php endforeach; ?>
                            
                        </div>
                        <small style="color:#777; display:block; margin-top:5px;">L'ordre de sélection déterminera l'ordre des étapes pour le client.</small>
                    </div>

                    <div class="form-group">
                        <label>Disponibilité (Optionnel) :</label>
                        <small style="color:#777;">Ajoutez les créneaux où cette formule est active.</small>
                        
                        <table id="table-conditions" style="width:100%; margin-top:5px; border-collapse:collapse;">
                            </table>

                        <button type="button" onclick="ajouterLigneCondition()" style="background:#eee; border:1px solid #ddd; padding:5px 10px; margin-top:5px; cursor:pointer; border-radius:4px;">
                            + Ajouter un créneau
                        </button>
                    </div>

                    <script>
                        // Liste des jours pour le select
                        const jours = {1:'Lundi', 2:'Mardi', 3:'Mercredi', 4:'Jeudi', 5:'Vendredi', 6:'Samedi', 7:'Dimanche'};

                        function ajouterLigneCondition() {
                            const table = document.getElementById('table-conditions');
                            const row = table.insertRow();
                            
                            // Cellule Jour
                            const cell1 = row.insertCell(0);
                            let selectHtml = '<select name="cond_jour[]" style="padding:5px;">';
                            for (const [k, v] of Object.entries(jours)) {
                                selectHtml += `<option value="${k}">${v}</option>`;
                            }
                            selectHtml += '</select>';
                            cell1.innerHTML = selectHtml;

                            // Cellule Début
                            const cell2 = row.insertCell(1);
                            cell2.innerHTML = '<input type="time" name="cond_debut[]" required style="padding:5px;">';

                            // Cellule Fin
                            const cell3 = row.insertCell(2);
                            cell3.innerHTML = '<input type="time" name="cond_fin[]" required style="padding:5px;">';

                            // Cellule Suppression
                            const cell4 = row.insertCell(3);
                            cell4.innerHTML = '<button type="button" onclick="this.parentElement.parentElement.remove()" style="color:red; border:none; background:none; cursor:pointer;">&times;</button>';
                        }
                        
                        // On ajoute une ligne par défaut au chargement (optionnel)
                        // ajouterLigneCondition(); 
                    </script>

                    <button type="submit" class="btn-submit">Créer la formule</button>
                </form>
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
                    <form method="POST" action="espace_restaurateur.php?page=horaires">
                        <input type="hidden" name="action" value="add_horaire">

                        <div class="form-group">
                            <label>Jour :</label>
                            <select name="jour" required>
                                <?php foreach($jours_semaine as $num => $nom): ?>
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

</body>
</html>