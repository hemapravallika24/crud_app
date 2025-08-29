<?php
require_once __DIR__ . '/config.php';

if (!is_logged_in()) {
    die("You must be logged in to delete a post.");
}

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$post_id = (int) $_GET['id'];
$current_user_id = $_SESSION['user_id']; // logged-in user

// fetch post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found.");
}

// check ownership
if ($post['user_id'] !== $current_user_id) {
    die("You are not allowed to delete this post.");
}

// delete post
$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$post_id]);

header("Location: index.php");
exit;
