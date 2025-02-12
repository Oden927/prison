<?php
session_start();
require '../config/database.php';

// Vérifier si l'utilisateur est bien un prisonnier
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'prisonnier') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Prisonnier</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Bienvenue, Prisonnier</h2>
    <a href="emploi.php">📅 Voir mon emploi du temps</a>
    <a href="chat.php">💬 Accéder au chat</a>
    <a href="cantine.php">🍽 Voir le menu de la cantine</a>
    <a href="../logout.php">🔴 Déconnexion</a>
</body>
</html>
