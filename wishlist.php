<?php
session_start();
require_once __DIR__ . '/config/db.php';

include 'includes/header.php';

// Initialiser un tableau vide pour les produits de la wishlist
$produits = [];
if (isset($_SESSION['wishlist']) && !empty($_SESSION['wishlist'])) {
    // Préparer une requête pour récupérer les produits de la wishlist
    $placeholders = implode(',', array_fill(0, count($_SESSION['wishlist']), '?'));
    $query = "SELECT id, nom, prix, prix_promotion, image_principale FROM produits WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($query);

    // Lier les IDs de la wishlist comme paramètres
    $types = str_repeat('i', count($_SESSION['wishlist']));
    $stmt->bind_param($types, ...$_SESSION['wishlist']);
    $stmt->execute();
    $result = $stmt->get_result();
    $produits = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - KivuPièces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body>
    <div class="wishlist-container">
        <h1 class="heading animate__animated animate__fadeInDown">Votre <span>Wishlist</span></h1>
        <div class="wishlist-content animate__animated animate__fadeIn">
            <?php if (!empty($produits)): ?>
                <div class="row g-4">
                    <?php foreach ($produits as $produit): ?>
                        <div class="col-md-4 col-lg-3">
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="assets/images/produits/<?= htmlspecialchars($produit['image_principale']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
                                    <button class="wishlist-btn active" data-id="<?= $produit['id'] ?>">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                                <div class="product-info">
                                    <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                                    <div class="price">
                                        <?= number_format($produit['prix_promotion'] > 0 ? $produit['prix_promotion'] : $produit['prix'], 2, ',', ' ') ?> €
                                        <?php if ($produit['prix_promotion'] > 0): ?>
                                            <span class="original-price"><?= number_format($produit['prix'], 2, ',', ' ') ?> €</span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="product_detail.php?id=<?= $produit['id'] ?>" class="btn">Voir le produit</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="wishlist-vide animate__animated animate__fadeIn">
                    <p>Votre wishlist est vide.</p>
                    <a href="index.php" class="btn">Retour à la boutique</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>