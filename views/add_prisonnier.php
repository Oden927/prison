<?php
session_start();
require '../config/database.php';

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Récupérer la liste des cellules
$stmt = $pdo->query("SELECT * FROM cellules");
$cellules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des prisonniers (sans cellule)
$stmt = $pdo->query("SELECT id, nom, prenom FROM utilisateurs WHERE role_id = (SELECT id FROM roles WHERE nom = 'prisonnier') AND id NOT IN (SELECT utilisateur_id FROM prisonniers_cellules)");
$prisonniers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilisateur_id = $_POST['utilisateur_id'];
    $cellule_id = $_POST['cellule_id'];
    $date_affectation = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO prisonniers_cellules (utilisateur_id, cellule_id, date_affectation) VALUES (?, ?, ?)");
    $stmt->execute([$utilisateur_id, $cellule_id, $date_affectation]);

    header("Location: prisonniers.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affecter un Prisonnier à une Cellule</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Affecter un Prisonnier à une Cellule</h2>
    <form action="add_prisonnier.php" method="POST">
        <label>Prisonnier :</label>
        <select name="utilisateur_id" required>
            <?php foreach ($prisonniers as $prisonnier) : ?>
                <option value="<?= $prisonnier['id'] ?>"><?= htmlspecialchars($prisonnier['nom'] . ' ' . $prisonnier['prenom']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Cellule :</label>
        <select name="cellule_id" required>
            <?php foreach ($cellules as $cellule) : ?>
                <option value="<?= $cellule['id'] ?>">Cellule <?= htmlspecialchars($cellule['numero']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Affecter</button>
    </form>
</body>
</html>
