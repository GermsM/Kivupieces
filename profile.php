<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
require_once __DIR__ . '/config/db.php';

$success = null;
$error = null;

// Update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = secure($_POST['fullname']);
    $email = secure($_POST['email']);
    $phone = secure($_POST['phone']);

    $query = "UPDATE utilisateurs SET nom = ?, email = ?, telephone = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $nom, $email, $phone, $_SESSION['user']['id']);
    
    if ($stmt->execute()) {
        $_SESSION['user']['nom'] = $nom;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['phone'] = $phone;
        $success = "Profil mis à jour avec succès.";
    } else {
        $error = "Erreur lors de la mise à jour du profil.";
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
            <li><a href="profile.php" class="active"><i class="fas fa-user"></i> Mon profil</a></li>
            <li><a href="orders.php"><i class="fas fa-box"></i> Mes commandes</a></li>
            <li><a href="addresses.php"><i class="fas fa-map-marker-alt"></i> Mes adresses</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </div>
    
    <div class="account-content">
        <h2>Mon compte</h2>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="account-section">
            <h3><i class="fas fa-user-cog"></i> Informations personnelles</h3>
            <form action="profile.php" method="post">
                <div class="form-group">
                    <label for="fullname">Nom complet</label>
                    <input type="text" name="fullname" id="fullname" value="<?php echo htmlspecialchars($_SESSION['user']['nom']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($_SESSION['user']['phone'] ?? ''); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
        
        <div class="account-section">
            <h3><i class="fas fa-lock"></i> Sécurité du compte</h3>
            <a href="change_password.php" class="btn btn-secondary">Changer le mot de passe</a>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
