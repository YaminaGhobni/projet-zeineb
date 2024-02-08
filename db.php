<?php
// Paramètres de connexion à la base de données
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'club';

// Connexion à la base de données
$conn = new mysqli($host, $username, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
