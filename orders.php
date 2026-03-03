<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/config/db.php';

// Fetch orders
$userId = $_SESSION['user']['id'];
$query = "SELECT c.*, cp.produit_id, cp.quantite, cp.prix_unitaire, p.nom AS produit_nom 
          FROM commandes c 
          LEFT JOIN commande_produits cp ON c.id = cp.commande_id 
          LEFT JOIN produits p ON cp.produit_id = p.id 
          WHERE c.utilisateur_id = ? 
          ORDER BY c.date_commande DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[$row['id']]['info'] = [
        'id' => $row['id'],
        'total' => $row['total'],
        'statut' => $row['statut'],
        'date_commande' => $row['date_commande']
    ];
    if ($row['produit_id']) {
        $orders[$row['id']]['produits'][] = [
            'nom' => $row['produit_nom'],
            'quantite' => $row['quantite'],
            'prix_unitaire' => $row['prix_unitaire']
        ];
    }
}
$stmt->close();

include('includes/header.php');
?>

<div class="account-container">
    <div class="account-sidebar">
        <div class="user-profile">
            <img src="assets/images/users/default.png" alt="Profile">
            <h3><?php echo htmlspecialchars($_SESSION['user']['nom']); ?></h3>
            <p><?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
        </div>
        <ul class="account-menu">
            <li><a href="profile.php"><i class="fas fa-user"></i> Mon profil</a></li>
            <li><a href="orders.php" class="active"><i class="fas fa-box"></i> Mes commandes</a></li>
            <li><a href="addresses.php"><i class="fas fa-map-marker-alt"></i> Mes adresses</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>
    
    <div class="account-content">
        <h2>Mes commandes</h2>
        <div class="account-section">
            <?php if (empty($orders)): ?>
                <p>Aucune commande trouvée.</p>
            <?php else: ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>N° Commande</th>
                            <th>Date</th>
                            <th>Produits</th>
                            <th>Total</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['info']['id']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($order['info']['date_commande'])); ?></td>
                                <td>
                                    <?php if (!empty($order['produits'])): ?>
                                        <ul>
                                            <?php foreach ($order['produits'] as $produit): ?>
                                                <li><?php echo htmlspecialchars($produit['nom']); ?> (x<?php echo $produit['quantite']; ?>)</li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        Aucun produit
                                    <?php endif; ?>
                                </td>
                                <td><?php echo number_format($order['info']['total'], 2, ',', ' '); ?> €</td>
                                <td><?php echo ucfirst($order['info']['statut']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
