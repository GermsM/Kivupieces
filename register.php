<?php
session_start();
require_once 'config/db.php'; // Connexion à la base de données

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim(strtolower($_POST['email'])); // Forcer l'email en minuscules
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    // Vérifier si l'email existe déjà
    $check_query = "SELECT id FROM utilisateurs WHERE email = ?";
    $check_stmt = $conn->prepare($check_query);
    if ($check_stmt) {
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();
        if ($check_stmt->num_rows > 0) {
            $error = "Cet email est déjà utilisé.";
        } else {
            // Insérer l'utilisateur
            $query = "INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("sss", $nom, $email, $password);
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Compte créé avec succès ! Veuillez vous connecter.";
                    header('Location: login.php');
                    exit();
                } else {
                    $error = "Erreur lors de la création du compte : " . $conn->error;
                }
                $stmt->close();
            } else {
                $error = "Erreur de préparation de la requête : " . $conn->error;
            }
        }
        $check_stmt->close();
    } else {
        $error = "Erreur de préparation de la vérification : " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - KivuPièces</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="login-container">
        <h2>S'inscrire</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" style="font-size: 1.6rem; padding: 10px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <input type="text" name="nom" id="nom" required placeholder=" ">
                <label for="nom">Nom</label>
            </div>
            <div class="form-group">
                <input type="email" name="email" id="email" required placeholder=" " autocapitalize="none">
                <label for="email">Email</label>
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" required placeholder=" " autocapitalize="none">
                <label for="password">Mot de passe</label>
            </div>
            <button type="submit" class="btn-register">S'inscrire</button>
            <div class="login-links">
                <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
            </div>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>