<?php
require_once __DIR__ . '/config.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

// Pagination setup
$limit = 5; // posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Count total posts for pagination
if (!empty($search)) {
    $countStmt = $pdo->prepare("SELECT COUNT(*) as total 
                                FROM posts 
                                WHERE user_id = ? AND (title LIKE ? OR content LIKE ?)");
    $countStmt->execute([$_SESSION['user_id'], "%$search%", "%$search%"]);
} else {
    $countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM posts WHERE user_id = ?");
    $countStmt->execute([$_SESSION['user_id']]);
}
$totalPosts = $countStmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

// Fetch posts with search and pagination
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT posts.*, users.username 
                           FROM posts 
                           JOIN users ON posts.user_id = users.id 
                           WHERE posts.user_id = ? AND (posts.title LIKE ? OR posts.content LIKE ?)
                           ORDER BY posts.created_at DESC
                           LIMIT ? OFFSET ?");
    $stmt->execute([$_SESSION['user_id'], "%$search%", "%$search%", $limit, $offset]);
} else {
    $stmt = $pdo->prepare("SELECT posts.*, users.username 
                           FROM posts 
                           JOIN users ON posts.user_id = users.id 
                           WHERE posts.user_id = ?
                           ORDER BY posts.created_at DESC
                           LIMIT ? OFFSET ?");
    $stmt->execute([$_SESSION['user_id'], $limit, $offset]);
}

$posts = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Blog Posts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>My Posts</h2>
    <a href="create.php" class="btn btn-primary">+ New Post</a>
  </div>

  <!-- Search Form -->
  <form method="GET" class="mb-3">
      <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="Search posts..."
                 value="<?= htmlspecialchars($search) ?>">
          <button class="btn btn-outline-secondary" type="submit">Search</button>
      </div>
  </form>

  <?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
          <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
          <small class="text-muted">
            By <?= htmlspecialchars($post['username']) ?> | <?= $post['created_at'] ?>
          </small>
          <div class="mt-2">
            <a href="view.php?id=<?= $post['id'] ?>" class="btn btn-info btn-sm">View</a>
            <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="delete.php?id=<?= $post['id'] ?>" class="btn btn-danger btn-sm"
               onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Pagination Links -->
    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a>
          </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
            <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <li class="page-item">
            <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>

  <?php else: ?>
    <div class="alert alert-info">No posts found.</div>
  <?php endif; ?>
</div>
</body>
</html>
