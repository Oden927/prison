<?php
session_start();
require '../config/database.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Récupérer le menu existant
$stmt = $pdo->query("SELECT * FROM cantine");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mise à jour ou ajout d'un menu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jour = $_POST['jour'];
    $menu = $_POST['menu'];

    $stmt = $pdo->prepare("INSERT INTO cantine (jour, menu) VALUES (?, ?) ON DUPLICATE KEY UPDATE menu = ?");
    $stmt->execute([$jour, $menu, $menu]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter/Modifier le Menu</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Ajouter/Modifier le Menu</h2>
    <form action="add_menu.php" method="POST">
        <label>Jour :</label>
        <select name="jour">
            <option value="Lundi">Lundi</option>
            <option value="Mardi">Mardi</option>
            <option value="Mercredi">Mercredi</option>
            <option value="Jeudi">Jeudi</option>
            <option value="Vendredi">Vendredi</option>
            <option value="Samedi">Samedi</option>
            <option value="Dimanche">Dimanche</option>
        </select>

        <label>Menu :</label>
        <textarea name="menu" required></textarea>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>
