<?php
// config.php — DB connection + session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$DB_HOST = "127.0.0.1";
$DB_NAME = "blog";
$DB_USER = "root";
$DB_PASS = "";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    die("DB connection failed: " . htmlspecialchars($e->getMessage()));
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function current_user() {
    return is_logged_in() ? ['id' => $_SESSION['user_id'], 'username' => $_SESSION['username']] : null;
}
?>
