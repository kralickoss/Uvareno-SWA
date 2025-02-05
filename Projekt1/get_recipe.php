<?php
include('db_connection.php');

// SQL dotaz pro získání receptů
$sql = "SELECT * FROM recipes ORDER BY created_at DESC";
$result = $conn->query($sql);

$recipes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
}

echo json_encode($recipes); // Vrátí data ve formátu JSON

$conn->close();
?>
