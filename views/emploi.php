<?php
session_start();
require '../config/database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Récupérer l'emploi du temps
$stmt = $pdo->prepare("SELECT * FROM emplois_du_temps WHERE utilisateur_id = ? ORDER BY FIELD(jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'), heure_debut");
$stmt->execute([$user_id]);
$schedule = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du Temps</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<a href="emploi.php">📅 Voir mon emploi du temps</a>

    <h2>Emploi du Temps</h2>
    <a href="dashboard.php">⬅ Retour</a>
    <table border="1">
        <tr>
            <th>Jour</th>
            <th>Heure Début</th>
            <th>Heure Fin</th>
            <th>Activité</th>
        </tr>
        <?php foreach ($schedule as $entry) : ?>
            <tr>
                <td><?= $entry['jour'] ?></td>
                <td><?= $entry['heure_debut'] ?></td>
                <td><?= $entry['heure_fin'] ?></td>
                <td><?= htmlspecialchars($entry['activite']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
