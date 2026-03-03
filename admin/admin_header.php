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
</head>
<body>
    <header class="header">
        <a href="index.php" class="logo"><i class="fas fa-car"></i> KivuPièces</a>
        <nav class="navbar">
            <a href="index.php">Accueil</a>
            <a href="produits.php">Produits</a>
            <a href="about.php">À propos</a>
            <a href="contact.php">Contact</a>
        </nav>
        <div class="icons">
            <a href="wishlist.php" class="fas fa-heart"><span class="badge bg-danger" id="wishlist-count">0</span></a>
            <a href="panier.php" class="fas fa-shopping-cart"><span class="badge bg-danger" id="cart-count"><?php echo $cartCount; ?></span></a>
            <a href="login.php" class="fas fa-user"></a>
        </div>
    </header>
    <?php
// includes/admin_header.php
session_start();
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <a href="admin_dashboard.php" class="logo">
                <img src="assets/images/logo.png" alt="KivuPièces Admin">
            </a>
            <div class="nav-links">
                <a href="admin_dashboard.php">Tableau de bord</a>
                <a href="admin_products.php">Produits</a>
                <a href="admin_orders.php">Commandes</a>
                <a href="admin_users.php">Utilisateurs</a>
                <a href="logout.php">Déconnexion</a>
            </div>
            <div class="menu-toggle" id="menu-bar">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </header>