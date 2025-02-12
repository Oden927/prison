<?php
session_start();
require '../config/database.php';

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Vérifier que l'ID de l'utilisateur est bien passé
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Supprimer l'utilisateur
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: dashboard.php");
    exit;
}
?>
