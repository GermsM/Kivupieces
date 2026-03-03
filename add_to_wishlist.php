<?php
session_start();
header('Content-Type: application/json');

if (!isset($_GET['action']) || !isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit();
}

$action = $_GET['action'];
$productId = (int)$_GET['id'];

if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

if ($action === 'ajouter') {
    if (!in_array($productId, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $productId;
        echo json_encode([
            'success' => true,
            'message' => 'Produit ajouté à la wishlist !',
            'wishlistCount' => count($_SESSION['wishlist'])
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Produit déjà dans la wishlist.',
            'wishlistCount' => count($_SESSION['wishlist'])
        ]);
    }
} elseif ($action === 'supprimer') {
    $index = array_search($productId, $_SESSION['wishlist']);
    if ($index !== false) {
        unset($_SESSION['wishlist'][$index]);
        $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
        echo json_encode([
            'success' => true,
            'message' => 'Produit retiré de la wishlist !',
            'wishlistCount' => count($_SESSION['wishlist'])
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Produit non trouvé dans la wishlist.',
            'wishlistCount' => count($_SESSION['wishlist'])
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Action non valide',
        'wishlistCount' => count($_SESSION['wishlist'])
    ]);
}
exit();