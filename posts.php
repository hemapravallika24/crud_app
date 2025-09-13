<?php
// db.php content here (or include 'db.php')
$servername = "localhost";
$username = "root"; // your DB username
$password = "";     // your DB password
$dbname = "blog";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination setup
$limit = 5; // posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where = "";
$params = [];

if (!empty($search)) {
    $where = "WHERE posts.title LIKE ? OR posts.content LIKE ?";
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

// Fetch posts with limit and join users
$sql = "SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        $where 
        ORDER BY posts.created_at DESC 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if (!empty($where)) {
    $stmt->bind_param("ssii", $params[0], $params[1], $limit, $offset);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog Posts</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { margin-bottom: 20px; }
        input[type="text"] { padding: 6px; width: 200px; }
        button { padding: 6px 12px; cursor: pointer; }
        .post { margin-bottom: 15px; }
        .pagination a { margin: 0 5px; text-decoration: none; padding: 5px 10px; border: 1px solid #333; border-radius: 4px; }
        .pagination a.active { background: #333; color: white; }
        small { color: gray; }
    </style>
</head>
<body>

<!-- Search Form -->
<form method="GET" action="posts.php">
    <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<h2>Posts</h2>
<?php while ($row = $result->fetch_assoc()) : ?>
    <div class="post">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
        <small>Posted by <?php echo htmlspecialchars($row['username']); ?> on <?php echo $row['created_at']; ?></small>
    </div>
    <hr>
<?php endwhile; ?>

<!-- Pagination Links -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>">Next</a>
    <?php endif; ?>
</div>

</body>
</html>
