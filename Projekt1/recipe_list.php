<?php
require 'db_connection.php'; // Připojení k databázi

// SQL dotaz pro získání všech receptů
$sql = "SELECT * FROM recipes ORDER BY created_at DESC";
$stmt = $pdo->query($sql);
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seznam receptů</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Seznam receptů</h1>
        <nav>
            <a href="index.php">Úvod</a>
            <a href="add_recipe.php">Přidat recept</a>
            <a href="recipe_list.php">Seznam receptů</a>
            <a href="register.php">Registrace</a>
            <a href="logout.php">Odhlásit se</a>
        </nav>
    </header>

    <section class="recipe-list">
        <?php if (count($recipes) > 0): ?>
            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe">
                    <h2><?= htmlspecialchars($recipe['title']) ?></h2>
                    <p><?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
                    <a href="view_recipe.php?id=<?= $recipe['id'] ?>">Zobrazit více</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Žádné recepty nebyly nalezeny.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2024 Uvareno. Všechna práva vyhrazena.</p>
    </footer>
</body>
</html>
