<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/config/db.php';

$success = null;
$error = null;

// Fetch addresses
$userId = $_SESSION['user']['id'];
$query = "SELECT * FROM adresses WHERE utilisateur_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$addresses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Add new address
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adresse_ligne1 = secure($_POST['adresse_ligne1']);
    $adresse_ligne2 = secure($_POST['adresse_ligne2']);
    $ville = secure($_POST['ville']);
    $code_postal = secure($_POST['code_postal']);
    $pays = secure($_POST['pays']);

    $query = "INSERT INTO adresses (utilisateur_id, adresse_ligne1, adresse_ligne2, ville, code_postal, pays) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssss", $userId, $adresse_ligne1, $adresse_ligne2, $ville, $code_postal, $pays);
    
    if ($stmt->execute()) {
        $success = "Adresse ajoutée avec succès.";
        header("Refresh: 2; url=addresses.php");
    } else {
        $error = "Erreur lors de l'ajout de l'adresse.";
    }
}

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
            <li><a href="orders.php"><i class="fas fa-box"></i> Mes commandes</a></li>
            <li><a href="addresses.php" class="active"><i class="fas fa-map-marker-alt"></i> Mes adresses</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>
    
    <div class="account-content">
        <h2>Mes adresses</h2>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="account-section">
            <h3><i class="fas fa-map-marker-alt"></i> Ajouter une nouvelle adresse</h3>
            <form action="addresses.php" method="post">
                <div class="form-group">
                    <label for="adresse_ligne1">Adresse (Ligne 1)</label>
                    <input type="text" name="adresse_ligne1" id="adresse_ligne1" required>
                </div>
                <div class="form-group">
                    <label for="adresse_ligne2">Adresse (Ligne 2)</label>
                    <input type="text" name="adresse_ligne2" id="adresse_ligne2">
                </div>
                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input type="text" name="ville" id="ville" required>
                </div>
                <div class="form-group">
                    <label for="code_postal">Code postal</label>
                    <input type="text" name="code_postal" id="code_postal" required>
                </div>
                <div class="form-group">
                    <label for="pays">Pays</label>
                    <input type="text" name="pays" id="pays" required>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter l'adresse</button>
            </form>
        </div>
        
        <div class="account-section">
            <h3><i class="fas fa-list"></i> Mes adresses enregistrées</h3>
            <?php if (empty($addresses)): ?>
                <p>Aucune adresse enregistrée.</p>
            <?php else: ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Adresse</th>
                            <th>Ville</th>
                            <th>Code postal</th>
                            <th>Pays</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($addresses as $address): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($address['adresse_ligne1'] . ($address['adresse_ligne2'] ? ', ' . $address['adresse_ligne2'] : '')); ?></td>
                                <td><?php echo htmlspecialchars($address['ville']); ?></td>
                                <td><?php echo htmlspecialchars($address['code_postal']); ?></td>
                                <td><?php echo htmlspecialchars($address['pays']); ?></td>
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
