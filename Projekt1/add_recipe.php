<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id'])) {
    die("Nepřihlášený uživatel! Přihlaste se.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $ingredients = htmlspecialchars($_POST['ingredients']);
    $steps = htmlspecialchars($_POST['steps']);
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO recipes (user_id, title, description, ingredients, steps, created_at) 
            VALUES (:user_id, :title, :description, :ingredients, :steps, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $user_id,
        'title' => $title,
        'description' => $description,
        'ingredients' => $ingredients,
        'steps' => $steps,
    ]);

    // Zvýšení počtu přidaných receptů
    $updateRecipes = $pdo->prepare("UPDATE user_stats SET recipe_count = recipe_count + 1 WHERE user_id = :user_id");
    $updateRecipes->execute(['user_id' => $user_id]);

    header("Location: recipe.php");
    exit();
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
