<?php
session_start();
require '../config/database.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Récupérer les utilisateurs
$stmt = $pdo->query("SELECT id, nom, prenom, role FROM utilisateurs WHERE role IN ('gardien', 'prisonnier')");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilisateur_id = $_POST['utilisateur_id'];
    $jour = $_POST['jour'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $activite = $_POST['activite'];

    $stmt = $pdo->prepare("INSERT INTO emplois_du_temps (utilisateur_id, jour, heure_debut, heure_fin, activite) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$utilisateur_id, $jour, $heure_debut, $heure_fin, $activite]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Emploi du Temps</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Ajouter un Emploi du Temps</h2>
    <form action="add_emploi.php" method="POST">
        <label>Utilisateur :</label>
        <select name="utilisateur_id" required>
            <?php foreach ($users as $user) : ?>
                <option value="<?= $user['id'] ?>"><?= $user['nom'] ?> <?= $user['prenom'] ?> (<?= $user['role'] ?>)</option>
            <?php endforeach; ?>
        </select>

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

        <label>Heure Début :</label>
        <input type="time" name="heure_debut" required>

        <label>Heure Fin :</label>
        <input type="time" name="heure_fin" required>

        <label>Activité :</label>
        <input type="text" name="activite" required>

        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
