<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (empty($_SESSION['panier'])) {
    header("Location: panier.php");
    exit();
}

$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['prix'] * $item['quantite'];
}

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - KivuPièces</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="checkout-container">
        <h1 class="heading animate__animated animate__fadeInDown">Finaliser votre <span>commande</span></h1>
        <div class="d-flex gap-4 flex-wrap animate__animated animate__fadeIn">
            <div class="checkout-form">
                <h3>Informations de livraison</h3>
                <form action="process_checkout.php" method="POST">
                    <div class="form-group">
                        <label for="nom">Nom complet</label>
                        <input type="text" id="nom" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="adresse">Adresse</label>
                        <input type="text" id="adresse" name="adresse" required>
                    </div>
                    <div class="form-group">
                        <label for="ville">Ville</label>
                        <input type="text" id="ville" name="ville" required>
                    </div>
                    <div class="form-group">
                        <label for="code_postal">Code postal</label>
                        <input type="text" id="code_postal" name="code_postal" required>
                    </div>
                    <div class="form-group">
                        <label for="pays">Point de retrait</label>
                        <select id="pays" name="pays" required>
                            <option value="">Sélectioner</option>
                            <option value="France">Nguba</option>
                            <option value="Belgique">Panzi</option>
                            <option value="Suisse">Bagira</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Moyen de paiement</label>
                        <div class="payment-methods d-flex gap-3 flex-wrap">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="card" required>
                                <span class="payment-icon" data-tooltip="Carte bancaire">
                                    <i class="fas fa-credit-card"></i>
                                </span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="paypal" required>
                                <span class="payment-icon" data-tooltip="PayPal">
                                    <i class="fab fa-paypal"></i>
                                </span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="bank_transfer" required>
                                <span class="payment-icon" data-tooltip="Virement bancaire">
                                    <i class="fas fa-university"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit animate__animated animate__pulse animate__infinite">Confirmer la commande</button>
                </form>
            </div>
            <div class="order-summary">
                <h3>Récapitulatif de la commande</h3>
                <table class="summary-table">
                    <tr>
                        <td>Sous-total</td>
                        <td><?= number_format($total, 2, ',', ' ') ?> $</td>
                    </tr>
                    <tr>
                        <td>Livraison</td>
                        <td>10,00 $</td>
                    </tr>
                    <tr class="total">
                        <td>Total</td>
                        <td><?= number_format($total + 10, 2, ',', ' ') ?> $</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        // Gestion des moyens de paiement
        document.querySelectorAll('.payment-option input').forEach(input => {
            input.addEventListener('change', () => {
                document.querySelectorAll('.payment-option').forEach(option => {
                    option.classList.remove('selected');
                });
                input.closest('.payment-option').classList.add('selected');
            });
        });
    </script>
</body>
</html>