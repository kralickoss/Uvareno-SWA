<?php
session_start(); // Spustí nebo pokračuje existující relaci
session_unset(); // Smaže všechny proměnné v relaci
session_destroy(); // Zničí relaci

// Přesměrování na přihlašovací stránku po odhlášení
header("Location: index.php");
exit;
?>
