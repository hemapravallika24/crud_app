<?php require_once __DIR__ . '/header.php'; ?>
<?php if (!is_logged_in()) { header('Location: /login.php'); exit; } ?>

<?php
$title = $content = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === "") $errors[] = "Title is required.";
    if ($content === "") $errors[] = "Content is required.";

    if (!$errors) {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $_SESSION['user_id']]);
        header("Location: /");
        exit;
    }
}
?>

<h1 class="h3 mb-3">New Post</h1>

<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="POST">
  <div class="mb-3">
    <label class="form-label">Title</label>
    <input class="form-control" name="title" value="<?= htmlspecialchars($title) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Content</label>
    <textarea class="form-control" name="content" rows="6"><?= htmlspecialchars($content) ?></textarea>
  </div>
  <button class="btn btn-primary">Create</button>
  <a href="/" class="btn btn-link">Cancel</a>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>
