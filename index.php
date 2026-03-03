<?php
session_start();
require_once __DIR__ . '/config/db.php';

// Fonction pour récupérer les produits
function getProducts($conn, $limit = 20) {
    $sql = "SELECT p.*, c.nom AS categorie, m.nom AS marque, 
           (SELECT AVG(note) FROM avis WHERE produit_id = p.id AND statut = 'approuve') as note_moyenne
           FROM produits p
           JOIN categories c ON p.categorie_id = c.id
           JOIN marques m ON p.marque_id = m.id
           WHERE p.statut = 'disponible'
           ORDER BY p.date_ajout DESC
           LIMIT ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Récupérer les produits
$produits = getProducts($conn);

// Récupérer les avis approuvés
$avis_query = "SELECT a.*, u.prenom, u.nom 
              FROM avis a
              JOIN utilisateurs u ON a.utilisateur_id = u.id
              WHERE a.statut = 'approuve'
              ORDER BY a.date_avis DESC LIMIT 3";
$avis_result = mysqli_query($conn, $avis_query);
$avis = mysqli_fetch_all($avis_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KivuPièces - Pièces auto</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header Section -->
<header class="header">
<div id="menu-bar" class="fas fa-bars"></div>
    <a href="index.php" class="logo"><i class="fas fa-car"></i> KivuPièces</a>
    <nav class="navbar">
        <a href="index.php">Accueil</a>
        <a href="index.php#products">Produits</a>
        <a href="index.php#reviews">Avis</a>
        <a href="index.php#footer">Contact</a>
    </nav>
    <div class="icons">
        <a href="wishlist.php" class="fas fa-heart"><span class="badge bg-danger" id="wishlist-count">0</span></a>
        <a href="panier.php" class="fas fa-shopping-cart"><span class="badge bg-danger" id="cart-count">
            <?php echo isset($_SESSION['panier']) ? array_sum(array_column($_SESSION['panier'], 'quantite')) : 0; ?>
        </span></a>
        <a href="<?php echo isset($_SESSION['user']) ? 'profile.php' : 'login.php'; ?>" class="fas fa-user"></a>
    </div>
</header>

<!-- Hero Section -->
<section class="home" id="home">
    <div class="container">
        <div class="row">
            <div class="col-2 text-content">
                <h1 class="animate-slide-in">Donnez une seconde vie à votre véhicule !</h1>
                <p class="animate-slide-in">Trouvez les meilleures pièces pour votre véhicule à des prix imbattables.</p>
                <form action="recherche.php" method="GET" class="search-form animate-slide-in">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Rechercher une pièce..." required>
                        <button type="submit" class="btn-search"><img src="assets/images/search.png" alt="Rechercher" width="20"></button>
                    </div>
                </form>
                <a href="#products" class="btn animate-slide-in">Acheter maintenant</a>
            </div>
            <div class="col-2 image-content">
                <div class="hero-image-container">
                    <img src="assets/images/hero-image.jpg" alt="Pièce auto" loading="lazy" class="animate-fade-in">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Section -->
<section class="service">
    <div class="box-container">
        <div class="box">
            <i class="fas fa-shipping-fast"></i>
            <h3>Livraison rapide</h3>
            <p>Recevez vos pièces en 24-48h dans toute la région avec notre service express.</p>
        </div>
        <div class="box">
            <i class="fas fa-undo"></i>
            <h3>Retours faciles</h3>
            <p>14 jours pour changer d'avis et retourner les pièces non utilisées.</p>
        </div>
        <div class="box">
            <i class="fas fa-headset"></i>
            <h3>Support technique</h3>
            <p>Nos experts sont disponibles 7j/7 pour vous conseiller sur le choix des pièces.</p>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="products" id="products">
    <h1 class="heading">Nos derniers <span>produits</span></h1>
    <div class="box-container">
        <?php if (empty($produits)): ?>
            <p class="text-center">Aucun produit disponible pour le moment.</p>
        <?php else: ?>
            <?php foreach ($produits as $produit): ?>
                <div class="box">
                    <div class="product-image-container">
                        <a href="product_detail.php?id=<?php echo htmlspecialchars($produit['id']); ?>">
                            <img src="assets/images/produits/<?php echo htmlspecialchars($produit['image_principale']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                        </a>
                    </div>
                    <div class="content">
                        <h3><?php echo htmlspecialchars($produit['nom']); ?></h3>
                        <div class="price">
                            <?php echo number_format($produit['prix_promotion'] > 0 ? $produit['prix_promotion'] : $produit['prix'], 2, ',', ' '); ?> $
                            <?php if ($produit['prix_promotion'] > 0): ?>
                                <span><?php echo number_format($produit['prix'], 2, ',', ' '); ?> $</span>
                            <?php endif; ?>
                        </div>
                        <div class="stars">
                            <?php
                            // Placeholder for rating (you can integrate actual ratings from the database)
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= 4 ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                            }
                            ?>
                        </div>
                        <div class="product-actions">
                        <button class="wishlist-btn <?php echo $isInWishlist ? 'active' : ''; ?>" data-id="<?php echo $produit['id']; ?>">
                            <img src="assets/images/icons/heart.jpg" alt="Wishlist" width="24">
                        </button>
                        <button class="add-to-cart" data-id="<?php echo $produit['id']; ?>">Ajouter au panier</button>
                    </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Reviews Section -->
<section class="reviews" id="reviews">
    <h1 class="heading">Avis de nos <span>clients</span></h1>
    <div class="reviews-container">
        <div class="review-card">
            <img src="assets/images/avatars/user1.jpg" alt="Client" class="review-avatar">
            <h3>Christelle Kal</h3>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <p>"Les pièces sont de très bonne qualité et la livraison rapide. Je recommande vivement KivuPièces !"</p>
        </div>
        <div class="review-card">
            <img src="assets/images/avatars/user2.jpg" alt="Client" class="review-avatar">
            <h3>Germ's MK</h3>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <p>"Service client au top et prix compétitifs. Quelques délais de livraison mais rien de grave."</p>
        </div>
        <div class="review-card">
            <img src="assets/images/avatars/user3.jpg" alt="Client" class="review-avatar">
            <h3>Pascal Pac</h3>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <p>"Achat facile et produits conformes. Le site est très clair et agréable à utiliser."</p>
        </div>
    </div>
    <div class="review-nav">
        <button class="review-prev"><i class="fas fa-chevron-left"></i></button>
        <button class="review-next"><i class="fas fa-chevron-right"></i></button>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter">
    <div class="content">
        <h3>Newsletter mensuelle</h3>
        <p>Abonnez-vous à notre newsletter pour recevoir nos offres spéciales et les nouveaux arrivages en exclusivité.</p>
        <form action="newsletter.php" method="POST">
            <input type="email" name="email" placeholder="Entrez votre email" class="box" required>
            <input type="submit" value="S'abonner" class="btn">
        </form>
    </div>
</section>

<!-- Footer Section -->
<?php include 'includes/footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
<script>
// Gestion de l'ajout au panier avec notification
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        
        <?php if (!isset($_SESSION['user'])): ?>
            window.location.href = 'login.php';
            return;
        <?php endif; ?>
        
        const productId = button.getAttribute('data-id');
        
        fetch(`panier.php?action=ajouter&id=${productId}`)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Notification
                    const notification = document.createElement('div');
                    notification.className = 'cart-notification';
                    notification.innerHTML = 'Produit ajouté au panier !';
                    document.body.appendChild(notification);
                    
                    // Disparaît après 3 secondes
                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                    
                    // Mise à jour du compteur
                    document.getElementById('cart-count').textContent = data.cartCount;
                }
            });
    });
});

// Menu responsive
document.getElementById('menu-bar').addEventListener('click', () => {
    document.querySelector('.navbar').classList.toggle('active');
});
</script>
</body>
</html>