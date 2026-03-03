<?php
session_start();
header('Content-Type: application/json');

if (!isset($_POST['id']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit();
}

$id = intval($_POST['id']);
$action = $_POST['action'];

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if ($action === 'supprimer') {
    if (isset($_SESSION['panier'][$id])) {
        unset($_SESSION['panier'][$id]);
        echo json_encode([
            'success' => true,
            'cartCount' => array_sum(array_column($_SESSION['panier'], 'quantite'))
        ]);
        exit();
    }
} elseif ($action === 'update' && isset($_POST['quantity'])) {
    $quantity = intval($_POST['quantity']);
    if ($quantity > 0 && isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]['quantite'] = $quantity;
        echo json_encode([
            'success' => true,
            'cartCount' => array_sum(array_column($_SESSION['panier'], 'quantite'))
        ]);
        exit();
    }
}

echo json_encode(['success' => false, 'message' => 'Action non valide']);
?>