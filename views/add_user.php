<?php
session_start();
require '../config/database.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Récupérer la liste des rôles disponibles
$stmt = $pdo->query("SELECT * FROM roles");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role_id = $_POST['role_id'];

    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $email, $password, $role_id]);

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Ajouter un utilisateur</h2>
    <form action="add_user.php" method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" required>
        
        <label>Prénom :</label>
        <input type="text" name="prenom" required>
        
        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        
        <label>Rôle :</label>
        <select name="role_id">
            <?php foreach ($roles as $role) : ?>
                <option value="<?= $role['id'] ?>"><?= $role['nom'] ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
