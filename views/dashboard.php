<?php
session_start();
require '../config/database.php';

// Vérifier si l'utilisateur est bien un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
// Récupérer la liste des utilisateurs (gardiens & prisonniers)
$stmt = $pdo->query("
    SELECT u.id, u.nom, u.prenom, u.email, r.nom AS role 
    FROM utilisateurs u
    JOIN roles r ON u.role_id = r.id
    WHERE r.nom IN ('gardien', 'prisonnier')
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Tableau de Bord Admin</h2>
    <a href="add_user.php">➕ Ajouter un utilisateur</a>
    <a href="../logout.php">🔴 Déconnexion</a>
    <a href="add_emploi.php">🗓 Ajouter un emploi du temps</a>
    <a href="chat.php">💬 Accéder au chat</a>
    <a href="cantine.php">🍽 Voir le menu de la cantine</a>
    <?php if ($_SESSION['role'] === 'admin') : ?>
       <a href="add_menu.php">✏ Modifier le menu</a>
    <?php endif; ?>
 
    <h3>Liste des Gardiens et Prisonniers</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['nom']) ?></td>
                <td><?= htmlspecialchars($user['prenom']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['role'] ?></td>
                <td>
                    <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">❌ Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
