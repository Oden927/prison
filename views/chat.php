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

// Récupérer la liste des utilisateurs du même rôle
$stmt = $pdo->prepare("SELECT id, nom, prenom FROM utilisateurs WHERE role = ? AND id != ?");
$stmt->execute([$role, $user_id]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les messages de cet utilisateur
$stmt = $pdo->prepare("
    SELECT m.*, u.nom AS sender_name, u.prenom AS sender_prenom 
    FROM messages m 
    JOIN utilisateurs u ON m.expediteur_id = u.id 
    WHERE (m.expediteur_id = ? OR m.recepteur_id = ?)
    ORDER BY date_envoi ASC
");
$stmt->execute([$user_id, $user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Envoi d'un message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['recepteur_id'], $_POST['message'])) {
    $recepteur_id = $_POST['recepteur_id'];
    $message = $_POST['message'];

    $stmt = $pdo->prepare("INSERT INTO messages (expediteur_id, recepteur_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $recepteur_id, $message]);

    header("Location: chat.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Chat entre <?= $role === 'prisonnier' ? 'prisonniers' : 'gardiens' ?></h2>
    <a href="dashboard.php">⬅ Retour</a>

    <h3>Messages</h3>
    <div style="border:1px solid #ccc; padding:10px; max-height:300px; overflow-y:scroll;">
        <?php foreach ($messages as $msg) : ?>
            <p><strong><?= htmlspecialchars($msg['sender_name'] . " " . $msg['sender_prenom']) ?>:</strong> <?= htmlspecialchars($msg['message']) ?> <small>(<?= $msg['date_envoi'] ?>)</small></p>
        <?php endforeach; ?>
    </div>

    <h3>Envoyer un message</h3>
    <form action="chat.php" method="POST">
        <label>Destinataire :</label>
        <select name="recepteur_id">
            <?php foreach ($users as $user) : ?>
                <option value="<?= $user['id'] ?>"><?= $user['nom'] ?> <?= $user['prenom'] ?></option>
            <?php endforeach; ?>
        </select>

        <label>Message :</label>
        <input type="text" name="message" required>

        <button type="submit">Envoyer</button>
    </form>
</body>
</html>
