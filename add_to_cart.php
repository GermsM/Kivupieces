<?php
session_start();
require_once __DIR__ . '/config/db.php';

header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Produit non spécifié']);
    exit();
}

$productId = intval($_POST['id']);

// Fetch product
$query = "SELECT id, nom, prix, image_principale FROM produits WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $productId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Produit non trouvé']);
    exit();
}

// Add to cart
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$found = false;
foreach ($_SESSION['panier'] as &$item) {
    if ($item['id'] == $productId) {
        $item['quantite']++;
        $found = true;
        break;
    }
}

if (!$found) {
    $_SESSION['panier'][] = [
        'id' => $product['id'],
        'nom' => $product['nom'],
        'prix' => $product['prix'],
        'quantite' => 1,
        'image' => $product['image_principale']
    ];
}

echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier']);
?>