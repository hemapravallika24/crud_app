require_once __DIR__ . '/config.php';

if (!is_logged_in()) {
    die("You must be logged in to delete a post.");
}

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$post_id = (int) $_GET['id'];

// check if post exists
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found.");
}

// just delete (no author check if your table doesn’t have one)
$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$post_id]);

header("Location: index.php");
exit;
