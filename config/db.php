<?php
// Configuration MySQLi
$host = 'sql203.infinityfree.com';
$dbname = 'if0_41298805_kivupieces';
$username = 'if0_41298805';
$password = 'NaMR1QBVpe9Wr';

// Création de la connexion
$conn = new mysqli($host, $username, $password, $dbname);

// Vérification des erreurs
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données: " . $conn->connect_error);
}

// Définition du charset
$conn->set_charset("utf8mb4");

// Fonction utilitaire pour sécuriser les sorties
function secure($data) {
    global $conn;
    return htmlspecialchars($conn->real_escape_string($data));
}
function secure_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}
