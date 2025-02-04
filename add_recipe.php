<?php
// Zahrnutí připojení k databázi
include('db_connection.php');

// Kontrola, zda byl formulář odeslán
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Získání hodnot z formuláře a ošetření HTML speciálních znaků
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $ingredients = htmlspecialchars($_POST['ingredients']);
    $steps = htmlspecialchars($_POST['steps']);
    $user_id = 1; // Placeholder pro ID uživatele (předpokládáme, že ID uživatele je 1)

    // Příprava SQL dotazu pro vložení receptu
    $sql = "INSERT INTO recipes (user_id, title, description, ingredients, steps, created_at) VALUES (:user_id, :title, :description, :ingredients, :steps, NOW())";
    $stmt = $pdo->prepare($sql);

    // Přiřazení hodnot do připravených parametrů
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':ingredients', $ingredients, PDO::PARAM_STR);
    $stmt->bindParam(':steps', $steps, PDO::PARAM_STR);

    // Pokus o vykonání dotazu
    if ($stmt->execute()) {
        // Přesměrování na stránku s receptem, pokud bylo přidání úspěšné
        header("Location: recipe.php");
        exit();
    } else {
        // Zobrazení chybové zprávy, pokud došlo k problému při přidávání receptu
        echo "Chyba při přidávání receptu.";
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přidat nový recept</title>
    <link rel="stylesheet" href="add_recipe-styles.css?v=1">
</head>
<body>
    <header>
        <h1>Uvareno - Přidat nový recept</h1>
        <nav>
            <a href="index.php">Úvod</a>
            <a href="add_recipe.php">Přidat recept</a>
            <a href="recipe.php">Sezam receptů</a>
            <a href="register.php">Registrace</a>
            <a href="logout.php">Odhlásit se</a>
        </nav>
    </header>

    <section class="add-recipe">
        <form id="recipe-form" action="add_recipe.php" method="POST">
            <label for="title">Název receptu</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Popis</label>
            <textarea id="description" name="description" required></textarea>

            <label for="ingredients">Ingredience</label>
            <textarea id="ingredients" name="ingredients" required></textarea>

            <label for="steps">Postup</label>
            <textarea id="steps" name="steps" required></textarea>

            <button type="submit">Přidat recept</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 Uvareno. Všechna práva vyhrazena.</p>
    </footer>
</body>
</html>
