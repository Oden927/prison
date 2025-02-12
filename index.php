<?php
session_start();
require 'config/database.php';

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Récupérer l'utilisateur avec son rôle
    $stmt = $pdo->prepare("
        SELECT utilisateurs.id, utilisateurs.nom, utilisateurs.prenom, utilisateurs.mot_de_passe, utilisateurs.role_id, roles.nom AS role
        FROM utilisateurs
        JOIN roles ON utilisateurs.role_id = roles.id
        WHERE utilisateurs.email = ?
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; // Maintenant stocké en tant que nom du rôle
        
        // Redirection selon le rôle
        if ($user['role'] == 'admin') {
            header("Location: views/dashboard.php");
        } elseif ($user['role'] == 'gardien') {
            header("Location: views/gardien.php");
        } else {
            header("Location: views/prisonnier.php");
        }
        exit;
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>
