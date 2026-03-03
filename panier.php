<?php
session_start();
require_once __DIR__ . '/config/db.php';

// Ajout au panier
if (isset($_GET['action']) && $_GET['action'] == 'ajouter' && isset($_GET['id'])) {
    $productId = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    if ($product) {
        if (!isset($_SESSION['panier'][$productId])) {
            $_SESSION['panier'][$productId] = [
                'id' => $product['id'],
                'nom' => $product['nom'],
                'prix' => $product['prix'],
                'quantite' => 1,
                'image' => $product['image_principale']
            ];
        } else {
            $_SESSION['panier'][$productId]['quantite']++;
        }
    }
    $stmt->close();
    header("Location: panier.php");
    exit();
}

// Calcul du total
$total = 0;
if (!empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        $total += $item['prix'] * $item['quantite'];
    }
}

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - KivuPièces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="panier-container">
        <h1 class="heading">Votre <span>panier</span></h1>
        <div class="panier-content">
            <?php if (!empty($_SESSION['panier'])): ?>
                <table class="panier-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['panier'] as $id => $item): ?>
                            <tr data-id="<?= $id ?>">
                                <td>
                                    <img src="assets/images/produits/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['nom']) ?>">
                                    <?= htmlspecialchars($item['nom']) ?>
                                </td>
                                <td><?= number_format($item['prix'], 2, ',', ' ') ?> €</td>
                                <td>
                                    <div class="quantity-controls">
                                        <button>-</button>
                                        <span><?= $item['quantite'] ?></span>
                                        <button>+</button>
                                    </div>
                                </td>
                                <td><?= number_format($item['prix'] * $item['quantite'], 2, ',', ' ') ?> €</td>
                                <td>
                                    <a href="#" class="btn-supprimer" data-id="<?= $id ?>"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="panier-recap">
                    <h3>Total: <?= number_format($total, 2, ',', ' ') ?> €</h3>
                    <a href="checkout.php" class="btn-commander">Passer commande</a>
                    <a href="index.php" class="btn-continuer">Continuer mes achats</a>
                </div>
            <?php else: ?>
                <div class="panier-vide">
                    <p>Votre panier est vide</p>
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