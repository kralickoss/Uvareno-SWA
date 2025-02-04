<?php
session_start();
require 'db_connection.php'; // Připojení k databázi (soubor by měl obsahovat PDO připojení)

$error = null; // Inicializace proměnné pro chybu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Ověření, že e-mail a heslo nejsou prázdné
    if (empty($email) || empty($password)) {
        $error = "Vyplňte prosím všechna pole.";
    } else {
        // Dotaz na uživatele podle e-mailu
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ověření uživatele a hesla
        if ($user && password_verify($password, $user['password_hash'])) { // Ověření hesla vůči hash hodnotě
            // Nastavení relací
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Přesměrování dle role uživatele
            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php'); // Administrátor přesměrován na dashboard
            } else {
                header('Location: index.php'); // Ostatní uživatelé na hlavní stránku
            }
            exit;
        } else {
            $error = "Špatný e-mail nebo heslo!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přihlášení</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Přihlášení</h2>
        <!-- Zobrazení chyby, pokud existuje -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Heslo</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Přihlásit se</button>
        </form>
        <p class="mt-3">Nemáte účet? <a href="register.php">Registrujte se</a>.</p>
    </div>
</body>
</html>
