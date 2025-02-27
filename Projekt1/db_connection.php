<?php
$servername = "localhost";
$username = "kralicekp";
$password = "Kfkomamrad69-";
$dbname = "kralicekp_uvareno";

$dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Připojení k databázi selhalo: " . $e->getMessage());
}
?>
