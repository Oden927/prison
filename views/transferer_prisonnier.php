<?php
session_start();
require '../config/database.php';

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Récupérer l'ID du prisonnier
if (!isset($_GET['id'])) {
    header("Location: prisonniers.php");
    exit;
}
$prisonnier_id = $_GET['id'];

// Récupérer la liste des cellules
$stmt = $pdo->query("SELECT * FROM cellules");
$cellules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer l'info du prisonnier
$stmt = $pdo->prepare("
    SELECT u.nom, u.prenom, c.numero AS cellule_actuelle
    FROM utilisateurs u
    JOIN prisonniers_cellules p ON u.id = p.utilisateur_id
    JOIN cellules c ON p.cellule_id = c.id
    WHERE u.id = ?
");
$stmt->execute([$prisonnier_id]);
$prisonnier = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nouvelle_cellule_id = $_POST['cellule_id'];
    $date_affectation = date('Y-m-d');

    $stmt = $pdo->prepare("UPDATE prisonniers_cellules SET cellule_id = ?, date_affectation = ? WHERE utilisateur_id = ?");
    $stmt->execute([$nouvelle_cellule_id, $date_affectation, $prisonnier_id]);

    header("Location: prisonniers.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transférer un Prisonnier</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Transférer <?= htmlspecialchars($prisonnier['nom'] . ' ' . $prisonnier['prenom']) ?></h2>
    <p>Cellule actuelle : <?= htmlspecialchars($prisonnier['cellule_actuelle']) ?></p>

    <form action="transferer_prisonnier.php?id=<?= $prisonnier_id ?>" method="POST">
        <label>Nouvelle Cellule :</label>
        <select name="cellule_id" required>
            <?php foreach ($cellules as $cellule) : ?>
                <option value="<?= $cellule['id'] ?>">Cellule <?= htmlspecialchars($cellule['numero']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Transférer</button>
    </form>
</body>
</html>
