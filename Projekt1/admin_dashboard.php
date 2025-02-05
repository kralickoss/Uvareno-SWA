<?php
session_start();
require 'db_connection.php';

// Kontrola, zda je uživatel přihlášen a má roli admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Smazání receptu
if (isset($_GET['delete_recipe'])) {
    $recipe_id = intval($_GET['delete_recipe']);
    $stmt = $pdo->prepare("DELETE FROM recipes WHERE id = :id");
    $stmt->execute(['id' => $recipe_id]);
    header('Location: admin_dashboard.php?success=recipe_deleted');
    exit;
}

// Smazání uživatele
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']);
    // Zabráníme smazání admina (například ID 1)
    if ($user_id === 1) {
        header('Location: admin_dashboard.php?error=cannot_delete_admin');
        exit;
    }
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    header('Location: admin_dashboard.php?success=user_deleted');
    exit;
}

// Získání seznamu receptů
$recipes_stmt = $pdo->query("SELECT * FROM recipes ORDER BY created_at DESC");
$recipes = $recipes_stmt->fetchAll(PDO::FETCH_ASSOC);

// Získání seznamu uživatelů
$users_stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <a href="admin_dashboard.php">Admin Dashboard</a>
            <a href="logout.php">Odhlásit se</a>
        </nav>
    </header>
    <?php include 'admin_navbar.php';?>
    <main>
        <section>
            <h2>Správa receptů</h2>
            <?php if (!empty($recipes)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Název</th>
                            <th>Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recipes as $recipe): ?>
                            <tr>
                                <td><?= htmlspecialchars($recipe['id']) ?></td>
                                <td><?= htmlspecialchars($recipe['title']) ?></td>
                                <td>
                                    <a href="?delete_recipe=<?= $recipe['id'] ?>" onclick="return confirm('Opravdu chcete tento recept smazat?');">Smazat</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Žádné recepty nebyly nalezeny.</p>
            <?php endif; ?>
        </section>
        <section>
            <h2>Správa uživatelů</h2>
            <?php if (!empty($users)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>E-mail</th>
                            <th>Role</th>
                            <th>Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td>
                                    <?php if ($user['id'] !== 1): ?>
                                        <a href="?delete_user=<?= $user['id'] ?>" onclick="return confirm('Opravdu chcete tohoto uživatele smazat?');">Smazat</a>
                                    <?php else: ?>
                                        <span>Nelze smazat</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Žádní uživatelé nebyli nalezeni.</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Uvareno. Všechna práva vyhrazena.</p>
    </footer>
</body>
</html>
