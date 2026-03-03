<?php
// Calculer le nombre total d'articles dans le panier
$cartCount = 0;
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    $cartCount = array_sum(array_column($_SESSION['panier'], 'quantite'));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KivuPièces</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header class="header">
        <a href="index.php" class="logo"><i class="fas fa-car"></i> KivuPièces</a>
        <nav class="navbar">
            <a href="index.php">Accueil</a>
            <a href="index.php#products">Produits</a>
            <a href="index.php#footer">À propos</a>
            <a href="index.php#footer">Contact</a>
        </nav>
        <div class="icons">
        <a href="wishlist.php"><img src="assets/images/icons/heart1.jpg" alt="Wishlist" width="30"><span class="counter wishlist-counter"><?php echo $_SESSION['wishlist_count'] ?? 0; ?></span></a>
        <a href="panier.php"><img src="assets/images/icons/cart.png" alt="Panier" width="30"><span class="counter cart-counter"><?php echo count($_SESSION['panier'] ?? []); ?></span></a>
        <a href="profile.php"><img src="assets/images/icons/user.png" alt="Profil" width="30"></a>
        <div id="menu-bar"><img src="assets/images/icons/menu.png" alt="Menu" width="24"></div>
    </div>
    </header>
</body>