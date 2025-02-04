<?php
require 'db_connection.php'; // Připojení k databázi

// Zkontroluj, jestli je nastaveno ID receptu
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Neplatné ID receptu.');
}

$recipe_id = intval($_GET['id']);

// SQL dotaz pro získání detailů receptu
$sql = "SELECT * FROM recipes WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $recipe_id]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipe) {
    die('Recept nenalezen.');
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($recipe['title'] ?? 'Neznámý recept') ?> - Detail receptu</title>
    <link rel="stylesheet" href="view_recipe.css">
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($recipe['title'] ?? 'Neznámý recept') ?></h1>
        <nav>
            <a href="index.php">Úvod</a>
            <a href="add_recipe.php">Přidat recept</a>
            <a href="recipe_list.php">Seznam receptů</a>
            <a href="register.php">Registrace</a>
            <a href="logout.php">Odhlásit se</a>
        </nav>
    </header>

    <section class="recipe-detail">
        <h2>Detail receptu</h2>
        
        <div class="recipe-section">
            <h3>Popis</h3>
            <p><?= nl2br(htmlspecialchars($recipe['description'] ?? 'Popis není k dispozici.')) ?></p>
        </div>
        
        <div class="recipe-section">
            <h3>Suroviny</h3>
            <ul>
                <?php
                $ingredients = isset($recipe['ingredients']) ? explode("\n", $recipe['ingredients']) : [];
                if (!empty($ingredients)) {
                    foreach ($ingredients as $ingredient) {
                        echo '<li>' . htmlspecialchars($ingredient) . '</li>';
                    }
                } else {
                    echo '<li>Žádné suroviny nejsou k dispozici.</li>';
                }
                ?>
            </ul>
        </div>
        
        <div class="recipe-section">
            <h3>Postup</h3>
            <ol>
                <?php
                $steps = isset($recipe['steps']) ? explode("\n", $recipe['steps']) : [];
                if (!empty($steps)) {
                    foreach ($steps as $step) {
                        echo '<li>' . htmlspecialchars($step) . '</li>';
                    }
                } else {
                    echo '<li>Postup není k dispozici.</li>';
                }
                ?>
            </ol>
        </div>

        <div class="recipe-meta">
            <p><strong>Vytvořeno:</strong> <?= htmlspecialchars($recipe['created_at'] ?? 'Neznámé datum') ?></p>
        </div>

        <a href="recipe_list.php" class="back-button">Zpět na seznam receptů</a>
    </section>

    <footer>
        <p>&copy; 2024 Uvareno. Všechna práva vyhrazena.</p>
    </footer>
</body>
</html>
