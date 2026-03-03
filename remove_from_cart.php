<?php
session_start();
header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Produit non spécifié']);
    exit();
}

$id = intval($_POST['id']);

if (isset($_SESSION['panier'][$id])) {
    unset($_SESSION['panier'][$id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Produit non trouvé']);
}
?>