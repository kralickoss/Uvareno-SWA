<?php
include('db_connection.php');
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uvareno - Recepty</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Uvareno - Nejlepší recepty</h1>
        <nav>
            <a href="index.php">Úvod</a>
            <a href="add_recipe.php">Přidat recept</a>
            <a href="recipe.php">Seznam receptů</a>
            <a href="register.php">Registrace</a>
            <a href="logout.php">Odhlásit se</a>
        </nav>
    </header>

    <!-- Vyhledávání -->
    <section class="search">
        <input type="text" id="search-bar" placeholder="Hledat recepty...">
        <button onclick="searchRecipes()">Hledat</button>
    </section>

    <!-- Seznam receptů -->
    <section id="recipe-list" class="recipe-list">
        <?php
        // Načítání receptů z databáze
        try {
            $sql = "SELECT * FROM recipes ORDER BY created_at DESC";
            $stmt = $pdo->query($sql); // Používáme správnou proměnnou $pdo
            $recipes = $stmt->fetchAll();

            // Výpis receptů
            if ($recipes) {
                foreach ($recipes as $recipe) {
                    echo "<div class='recipe'>";
                    echo "<h2>" . htmlspecialchars($recipe['title']) . "</h2>";
                    echo "<p>" . htmlspecialchars($recipe['description']) . "</p>";
                    echo "<a href='view_recipe.php?id=" . $recipe['id'] . "'>Zobrazit více</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>Žádné recepty nejsou k dispozici.</p>";
            }
        } catch (PDOException $e) {
            echo "Chyba při načítání receptů: " . $e->getMessage();
        }
        ?>
    </section>

    <footer>
        <p>&copy; 2024 Uvareno. Všechna práva vyhrazena.</p>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>
