<?php require_once __DIR__ . '/header.php'; ?>

<?php
// Fetch posts
$stmt = $pdo->query("SELECT p.id, p.title, p.content, p.created_at, p.user_id, u.username
                     FROM posts p
                     LEFT JOIN users u ON u.id = p.user_id
                     ORDER BY p.created_at DESC");
$posts = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3">All Posts</h1>
  <?php if (is_logged_in()): ?>
    <a href="/crud_app/create.php" class="btn btn-primary">+ New Post</a>
  <?php endif; ?>
</div>

<?php if (empty($posts)): ?>
  <div class="alert alert-info">No posts yet.</div>
<?php else: ?>
  <div class="list-group">
    <?php foreach ($posts as $post): ?>
      <div class="list-group-item">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1"><?= htmlspecialchars($post['title']) ?></h5>
          <small><?= htmlspecialchars($post['created_at']) ?></small>
        </div>
        <p class="mb-1"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        <small>By <?= htmlspecialchars($post['username'] ?? 'Unknown') ?></small>

        <div class="mt-2">
          <a href="/crud_app/view.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-info">View</a>

          <?php if (is_logged_in() && $post['user_id'] == $_SESSION['user_id']): ?>
            <a href="/crud_app/edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="/crud_app/delete.php?id=<?= $post['id'] ?>" 
               class="btn btn-sm btn-danger"
               onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
