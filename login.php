<?php
session_start();
require_once 'config/db.php'; // Assurez-vous que ce fichier existe pour la connexion à la base de données

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = secure_input($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - KivuPièces</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="login-container">
        <h2>Se connecter</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" style="font-size: 1.6rem; padding: 10px;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" name="email" id="email" required placeholder=" ">
                <label for="email">Email</label>
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" required placeholder=" ">
                <label for="password">Mot de passe</label>
            </div>
            <button type="submit" class="btn-login">Se connecter</button>
            <div class="login-links">
                <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
            </div>
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>

</body>
</html>