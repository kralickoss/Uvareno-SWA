<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validace vstupů
    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Vyplňte všechna pole.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Zadejte platný e-mail.";
    } elseif ($password !== $confirm_password) {
        $error = "Hesla se neshodují.";
    } else {
        try {
            // Hash hesla
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Vložení uživatele do databáze
            $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :password)");
            $stmt->execute([
                'email' => $email,
                'password' => $hashed_password,
            ]);

            header('Location: login.php?success=registered');
            exit;
        } catch (PDOException $e) {
            // Zpracování chyb při duplikaci e-mailu
            if ($e->getCode() === '23000') {
                $error = "E-mail již existuje.";
            } else {
                $error = "Registrace selhala: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <h2 class="text-center">Registrace</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" action="register.php">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Heslo</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Potvrďte heslo</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block mt-3">Registrovat se</button>
                    <div class="mt-2 text-center">
                        <a href="login.php">Máte již účet? Přihlaste se</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
