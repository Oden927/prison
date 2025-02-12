<?php
$host = "localhost";
$dbname = "prison_management";
$username = "root";  // Change si nécessaire
$password = "alia";  // Mets le mot de passe que tu as défini

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
