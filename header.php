<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog CRUD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/">Blog CRUD</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/">Posts</a></li>
        <?php if (is_logged_in()): ?>
          <li class="nav-item"><a class="nav-link" href="/create.php">New Post</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (is_logged_in()): ?>
          <li class="nav-item"><span class="navbar-text me-2">Hi, <?= htmlspecialchars($_SESSION['username']) ?></span></li>
          <li class="nav-item"><a class="nav-link" href="/logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container my-4">
