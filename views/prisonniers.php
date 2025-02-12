<?php
session_start();
require '../config/database.php';

// Vérifier si l'utilisateur est un admin ou un gardien
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'gardien')) {
    header("Location: login.php");
    exit;
}

// Récupérer la liste des prisonniers avec leur cellule
$stmt = $pdo->query("
    SELECT u.id, u.nom, u.prenom, c.numero AS cellule, p.date_affectation
    FROM utilisateurs u
    JOIN prisonniers_cellules p ON u.id = p.utilisateur_id
    JOIN cellules c ON p.cellule_id = c.id
    ORDER BY c.numero
");
$prisonniers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Prisonniers</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Liste des Prisonniers</h2>
    <a href="add_prisonnier.php">➕ Affecter un prisonnier</a>
    <table border="1">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Cellule</th>
            <th>Date d'affectation</th>
            <th>Action</th>
        </tr>
        <?php foreach ($prisonniers as $prisonnier) : ?>
            <tr>
                <td><?= htmlspecialchars($prisonnier['nom']) ?></td>
                <td><?= htmlspecialchars($prisonnier['prenom']) ?></td>
                <td><?= htmlspecialchars($prisonnier['cellule']) ?></td>
                <td><?= $prisonnier['date_affectation'] ?></td>
                <td><a href="transferer_prisonnier.php?id=<?= $prisonnier['id'] ?>">🔄 Transférer</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
