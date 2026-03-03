<?php
require_once __DIR__ . '/config/db.php';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$produit_id = (int)$_GET['id'];
$query = "SELECT p.*, c.nom AS categorie, m.nom AS marque
          FROM produits p
          JOIN categories c ON p.categorie_id = c.id
          JOIN marques m ON p.marque_id = m.id
          WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $produit_id);
$stmt->execute();
$produit = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$produit) {
    header("Location: index.php");
    exit;
}

$query = "SELECT image_url FROM images_produits WHERE produit_id = ? ORDER BY ordre";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $produit_id);
$stmt->execute();
$images = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$query = "SELECT AVG(note) AS moyenne, COUNT(*) AS total FROM avis WHERE produit_id = ? AND statut = 'approuve'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $produit_id);
$stmt->execute();
$note_data = $stmt->get_result()->fetch_assoc();
$moyenne = round($note_data['moyenne'] ?? 0, 1);
$total_avis = $note_data['total'];
$stmt->close();

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produit['nom']) ?> - KivuPièces</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="product-detail-container">
        <h1 class="heading"><?= htmlspecialchars($produit['nom']) ?></h1>
        <div class="row g-4">
            <div class="col-lg-5">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="product-image-container">
                                <img src="assets/images/produits/<?= htmlspecialchars($produit['image_principale']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
                            </div>
                        </div>
                        <?php foreach ($images as $image): ?>
                            <div class="carousel-item">
                                <div class="product-image-container">
                                    <img src="assets/images/produits/<?= htmlspecialchars($image['image_url']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($images) > 0): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="product-detail-content">
                    <h2><?= htmlspecialchars($produit['nom']) ?></h2>
                    <div class="d-flex align-items-center mb-3">
                        <div class="review-stars me-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= floor($moyenne)): ?>
                                    <i class="fas fa-star"></i>
                                <?php elseif ($i == ceil($moyenne) && $moyenne != floor($moyenne)): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <small>(<?= $total_avis ?> avis)</small>
                    </div>
                    <div class="price">
                        <?= number_format($produit['prix_promotion'] > 0 ? $produit['prix_promotion'] : $produit['prix'], 2, ',', ' ') ?> €
                        <?php if ($produit['prix_promotion'] > 0): ?>
                            <span class="original-price"><?= number_format($produit['prix'], 2, ',', ' ') ?> €</span>
                        <?php endif; ?>
                    </div>
                    <p class="mb-4"><?= htmlspecialchars($produit['description_courte']) ?></p>
                    <form method="GET" action="">
                        <input type="hidden" name="action" value="ajouter">
                        <input type="hidden" name="id" value="<?= $produit['id'] ?>">
                        <div class="quantity-controls">
                            <button type="button" class="btn-minus">-</button>
                            <input type="text" name="quantity" value="1" min="1" max="<?= $produit['quantite'] ?>">
                            <button type="button" class="btn-plus">+</button>
                        </div>
                        <button type="submit" class="btn-add-to-cart">
                            <i class="fas fa-shopping-cart me-1"></i> Ajouter au panier
                        </button>
                        <button type="button" class="wishlist-btn" data-id="<?= $produit['id'] ?>">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                    <div class="d-flex pt-2">
                        <strong class="text-dark me-3">Partager :</strong>
                        <div class="d-flex">
                            <a href="#" class="text-dark me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-dark me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-dark me-2"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="text-dark"><i class="fab fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col">
                <div class="bg-light p-4 rounded">
                    <ul class="nav nav-tabs" id="productTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">Description</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">Informations</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">Avis (<?= $total_avis ?>)</button>
                        </li>
                    </ul>
                    <div class="tab-content p-3" id="productTabContent">
                        <div class="tab-pane fade show active" id="description">
                            <h4>Description du produit</h4>
                            <p><?= nl2br(htmlspecialchars($produit['description'])) ?></p>
                        </div>
                        <div class="tab-pane fade" id="info">
                            <h4>Informations complémentaires</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li><strong>Catégorie :</strong> <?= htmlspecialchars($produit['categorie']) ?></li>
                                        <li><strong>Marque :</strong> <?= htmlspecialchars($produit['marque']) ?></li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li><strong>Référence :</strong> <?= htmlspecialchars($produit['reference']) ?></li>
                                        <li><strong>Garantie :</strong> <?= $produit['garantie'] ?> mois</li>
                                    </ul>
                                </div>
                            </div>
                            <?php if (!empty($produit['compatibilite'])): ?>
                                <div class="mt-3">
                                    <h5>Compatibilité</h5>
                                    <p><?= htmlspecialchars($produit['compatibilite']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane fade" id="reviews">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Avis clients</h4>
                                    <?php if ($total_avis > 0): ?>
                                        <p>Avis à implémenter.</p>
                                    <?php else: ?>
                                        <p>Aucun avis pour ce produit.</p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <h4>Laisser un avis</h4>
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label class="form-label">Votre note</label>
                                            <div class="review-stars">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="far fa-star" data-rating="<?= $i ?>"></i>
                                                <?php endfor; ?>
                                                <input type="hidden" name="note" id="rating-value" value="0">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="comment" class="form-label">Votre avis</label>
                                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Votre nom</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Envoyer l'avis</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        // Gestion de la quantité
        document.querySelectorAll('.btn-minus').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = btn.closest('.quantity-controls').querySelector('input');
                let value = parseInt(input.value);
                if (value > 1) input.value = value - 1;
            });
        });
        document.querySelectorAll('.btn-plus').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = btn.closest('.quantity-controls').querySelector('input');
                let value = parseInt(input.value);
                if (value < parseInt(input.max)) input.value = value + 1;
            });
        });

        // Gestion des étoiles pour les avis
        document.querySelectorAll('.review-stars i[data-rating]').forEach(star => {
            star.addEventListener('click', () => {
                const rating = parseInt(star.getAttribute('data-rating'));
                const stars = star.parentElement.querySelectorAll('i[data-rating]');
                stars.forEach((s, index) => {
                    s.classList.toggle('fas', index < rating);
                    s.classList.toggle('far', index >= rating);
                });
                document.getElementById('rating-value').value = rating;
            });
        });
    </script>
</body>
</html>

