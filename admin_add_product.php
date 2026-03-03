<?php
// Inclure la configuration de la base de données
require_once __DIR__ . '/config/db.php';

// Récupérer les catégories, marques et modèles pour les menus déroulants
$categories_query = "SELECT * FROM categories ORDER BY nom";
$categories_result = mysqli_query($conn, $categories_query);
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);

$marques_query = "SELECT * FROM marques ORDER BY nom";
$marques_result = mysqli_query($conn, $marques_query);
$marques = mysqli_fetch_all($marques_result, MYSQLI_ASSOC);

$modeles_query = "SELECT * FROM modeles ORDER BY nom";
$modeles_result = mysqli_query($conn, $modeles_query);
$modeles = mysqli_fetch_all($modeles_result, MYSQLI_ASSOC);

// Traitement du formulaire
$success = null;
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et sécuriser les données
    $reference = secure($_POST['reference']);
    $nom = secure($_POST['nom']);
    $description = secure($_POST['description']);
    $description_courte = secure($_POST['description_courte']);
    $prix = (float)$_POST['prix'];
    $prix_promotion = !empty($_POST['prix_promotion']) ? (float)$_POST['prix_promotion'] : 0;
    $quantite = (int)$_POST['quantite'];
    $compatibilite = secure($_POST['compatibilite']);
    $garantie = (int)$_POST['garantie'];
    $statut = secure($_POST['statut']);

    // Gestion de la catégorie
    if (!empty($_POST['nouvelle_categorie'])) {
        $nouvelle_categorie = secure($_POST['nouvelle_categorie']);
        $slug_categorie = strtolower(str_replace(' ', '-', $nouvelle_categorie));
        $query = "INSERT INTO categories (nom, slug) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $nouvelle_categorie, $slug_categorie);
        if ($stmt->execute()) {
            $categorie_id = $conn->insert_id;
        } else {
            $error = "Erreur lors de l'ajout de la catégorie : " . $conn->error;
        }
    } else {
        $categorie_id = (int)$_POST['categorie_id'];
    }

    // Gestion de la marque
    if (!empty($_POST['nouvelle_marque'])) {
        $nouvelle_marque = secure($_POST['nouvelle_marque']);
        $slug_marque = strtolower(str_replace(' ', '-', $nouvelle_marque));
        $query = "INSERT INTO marques (nom, slug) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $nouvelle_marque, $slug_marque);
        if ($stmt->execute()) {
            $marque_id = $conn->insert_id;
        } else {
            $error = "Erreur lors de l'ajout de la marque : " . $conn->error;
        }
    } else {
        $marque_id = (int)$_POST['marque_id'];
    }

    // Gestion du modèle
    if (!empty($_POST['nouveau_modele'])) {
        $nouveau_modele = secure($_POST['nouveau_modele']);
        $annee_debut = !empty($_POST['annee_debut']) ? (int)$_POST['annee_debut'] : null;
        $annee_fin = !empty($_POST['annee_fin']) ? (int)$_POST['annee_fin'] : null;
        $query = "INSERT INTO modeles (marque_id, nom, annee_debut, annee_fin) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isii", $marque_id, $nouveau_modele, $annee_debut, $annee_fin);
        if ($stmt->execute()) {
            $modele_id = $conn->insert_id;
        } else {
            $error = "Erreur lors de l'ajout du modèle : " . $conn->error;
        }
    } else {
        $modele_id = !empty($_POST['modele_id']) ? (int)$_POST['modele_id'] : null;
    }

    // Gestion de l'image principale
    $image_principale = '';
    if (isset($_FILES['image_principale']) && $_FILES['image_principale']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'assets/images/produits/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $image_name = uniqid() . '_' . basename($_FILES['image_principale']['name']);
        $image_path = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['image_principale']['tmp_name'], $image_path)) {
            $image_principale = $image_name;
        } else {
            $error = "Erreur lors du téléchargement de l'image principale.";
        }
    } else {
        $error = "Veuillez sélectionner une image principale.";
    }

    // Insérer le produit si aucune erreur
    if (!$error) {
        $query = "INSERT INTO produits (
            reference, categorie_id, marque_id, modele_id, nom, description, description_courte,
            prix, prix_promotion, quantite, compatibilite, garantie, image_principale, statut
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            $error = "Erreur lors de la préparation de la requête : " . $conn->error;
        } else {
            $stmt->bind_param(
                "siiisssddisiss",
                $reference, $categorie_id, $marque_id, $modele_id, $nom, $description, $description_courte,
                $prix, $prix_promotion, $quantite, $compatibilite, $garantie, $image_principale, $statut
            );

            if ($stmt->execute()) {
                $produit_id = $conn->insert_id;

                // Gestion des images supplémentaires
                if (!empty($_FILES['images_supplementaires']['name'][0])) {
                    foreach ($_FILES['images_supplementaires']['tmp_name'] as $index => $tmp_name) {
                        if ($_FILES['images_supplementaires']['error'][$index] === UPLOAD_ERR_OK) {
                            $image_name = uniqid() . '_' . basename($_FILES['images_supplementaires']['name'][$index]);
                            $image_path = $upload_dir . $image_name;

                            if (move_uploaded_file($tmp_name, $image_path)) {
                                $query = "INSERT INTO images_produits (produit_id, image_url, ordre) VALUES (?, ?, ?)";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("isi", $produit_id, $image_name, $index);
                                $stmt->execute();
                            }
                        }
                    }
                }

                $success = "Produit ajouté avec succès !";
            } else {
                $error = "Erreur lors de l'ajout du produit : " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit - KivuPièces Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="logo">KivuPièces Admin</div>
        <ul>
            <li><a href="admin_add_product.php"><i class="fas fa-plus"></i> Ajouter un produit</a></li>
            <li><a href="admin_manage_products.php"><i class="fas fa-list"></i> Gérer les produits</a></li>
            <li><a href="index.php"><i class="fas fa-home"></i> Retour à la boutique</a></li>
        </ul>
    </div>

    <!-- Contenu principal -->
    <div class="admin-content">
        <h1>Ajouter un produit</h1>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="admin_add_product.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="reference">Référence</label>
                        <input type="text" class="form-control" id="reference" name="reference" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="nom">Nom du produit</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="categorie_id">Catégorie</label>
                        <select class="form-control" id="categorie_id" name="categorie_id">
                            <option value="">Sélectionner une catégorie</option>
                            <?php foreach ($categories as $categorie): ?>
                                <option value="<?php echo $categorie['id']; ?>"><?php echo htmlspecialchars($categorie['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control mt-2" name="nouvelle_categorie" placeholder="Ou ajouter une nouvelle catégorie">
                    </div>
                    <div class="form-group mb-3">
                        <label for="marque_id">Marque</label>
                        <select class="form-control" id="marque_id" name="marque_id">
                            <option value="">Sélectionner une marque</option>
                            <?php foreach ($marques as $marque): ?>
                                <option value="<?php echo $marque['id']; ?>"><?php echo htmlspecialchars($marque['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control mt-2" name="nouvelle_marque" placeholder="Ou ajouter une nouvelle marque">
                    </div>
                    <div class="form-group mb-3">
                        <label for="modele_id">Modèle (optionnel)</label>
                        <select class="form-control" id="modele_id" name="modele_id">
                            <option value="">Aucun modèle</option>
                            <?php foreach ($modeles as $modele): ?>
                                <option value="<?php echo $modele['id']; ?>"><?php echo htmlspecialchars($modele['nom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control mt-2" name="nouveau_modele" placeholder="Ou ajouter un nouveau modèle">
                        <input type="number" class="form-control mt-2" name="annee_debut" placeholder="Année début (optionnel)">
                        <input type="number" class="form-control mt-2" name="annee_fin" placeholder="Année fin (optionnel)">
                    </div>
                    <div class="form-group mb-3">
                        <label for="prix">Prix (€)</label>
                        <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="prix_promotion">Prix promotionnel (€, optionnel)</label>
                        <input type="number" step="0.01" class="form-control" id="prix_promotion" name="prix_promotion">
                    </div>
                    <div class="form-group mb-3">
                        <label for="quantite">Quantité en stock</label>
                        <input type="number" class="form-control" id="quantite" name="quantite" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="description_courte">Description courte</label>
                        <textarea class="form-control" id="description_courte" name="description_courte" rows="3" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="description">Description détaillée</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="compatibilite">Compatibilité</label>
                        <input type="text" class="form-control" id="compatibilite" name="compatibilite">
                    </div>
                    <div class="form-group mb-3">
                        <label for="garantie">Garantie (mois)</label>
                        <input type="number" class="form-control" id="garantie" name="garantie" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="statut">Statut</label>
                        <select class="form-control" id="statut" name="statut" required>
                            <option value="disponible">Disponible</option>
                            <option value="rupture">Rupture</option>
                            <option value="bientot">Bientôt disponible</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="image_principale">Image principale</label>
                        <input type="file" class="form-control" id="image_principale" name="image_principale" accept="image/*" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="images_supplementaires">Images supplémentaires</label>
                        <input type="file" class="form-control" id="images_supplementaires" name="images_supplementaires[]" accept="image/*" multiple>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter le produit</button>
        </form>
    </div>

    <!-- Inclure le footer -->
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

