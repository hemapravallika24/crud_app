<?php
include 'db.php'; // database connection

// Pagination setup
$limit = 5; // posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where = "";
$params = [];

if (!empty($search)) {
    $where = "WHERE title LIKE ? OR content LIKE ?";
    $params = ["%$search%", "%$search%"];
}

// Count total posts
$countSql = "SELECT COUNT(*) as total FROM posts $where";
$stmt = $conn->prepare($countSql);
if (!empty($where)) {
    $stmt->bind_param("ss", $params[0], $params[1]);
}
$stmt->execute();
$countResult = $stmt->get_result()->fetch_assoc();
$totalPosts = $countResult['total'];
$totalPages = ceil($totalPosts / $limit);

// Fetch posts with limit
$sql = "SELECT * FROM posts $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if (!empty($where)) {
    $stmt->bind_param("ssii", $params[0], $params[1], $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Posts</h2>
<?php while ($row = $result->fetch_assoc()) : ?>
    <div class="post">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
        <small>Posted on <?php echo $row['created_at']; ?></small>
    </div>
    <hr>
<?php endwhile; ?>

<!-- Pagination Links -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>" 
           class="<?php echo ($i == $page) ? 'active' : ''; ?>">
           <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>">Next</a>
    <?php endif; ?>
</div>
