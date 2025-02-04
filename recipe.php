<?php
include('db_connection.php');

// Získání vyhledávacího dotazu z URL, pokud je nastaven
$query = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';

// Příprava SQL dotazu pro vyhledávání receptů
$sql = "SELECT * FROM recipes WHERE title LIKE :query ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);

// Přiřazení hodnoty pro vyhledávání
$search = "%$query%";
$stmt->bindParam(':query', $search, PDO::PARAM_STR);

// Vykonání dotazu
$stmt->execute();

// Získání výsledků
$result = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recepty</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Uvareno - Recepty</h1>
        <nav>
            <a href="index.php">Úvod</a>
            <a href="add_recipe.php">Přidat recept</a>
            <a href="recipe.php">Seznam receptů</a>
            <a href="register.php">Registrace</a>
            <a href="logout.php">Odhlásit se</a>
        </nav>
    </header>

    <section class="recipe-list">
        <?php if (count($result) > 0): ?>
            <?php foreach ($result as $recipe): ?>
                <div class="recipe">
                    <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                    <p><?php echo htmlspecialchars($recipe['description']); ?></p>
                    <a href="view_recipe.php?id=<?php echo $recipe['id']; ?>">Zobrazit více</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Žádné recepty nenalezeny.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2024 Uvareno. Všechna práva vyhrazena.</p>
    </footer>
</body>
</html>
