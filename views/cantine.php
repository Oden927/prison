<?php
session_start();
require '../config/database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Récupérer les menus de la cantine
$stmt = $pdo->query("SELECT * FROM cantine ORDER BY FIELD(jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche')");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de la Cantine</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Menu de la Cantine</h2>
    <a href="dashboard.php">⬅ Retour</a>

    <table border="1">
        <tr>
            <th>Jour</th>
            <th>Menu</th>
        </tr>
        <?php foreach ($menus as $menu) : ?>
            <tr>
                <td><?= $menu['jour'] ?></td>
                <td><?= htmlspecialchars($menu['menu']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

