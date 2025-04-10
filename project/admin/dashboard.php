<?php
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header("Location: index.php?page=login");
    exit;
}

// Total users
$userStmt = $pdo->query("SELECT COUNT(*) as total FROM users");
$userCount = $userStmt->fetch()['total'];

// Total courses
$courseStmt = $pdo->query("SELECT COUNT(*) as total FROM courses");
$courseCount = $courseStmt->fetch()['total'];

// Total revenue (from paid courses)
$revStmt = $pdo->query("
    SELECT SUM(c.price) as revenue
    FROM enrollments e
    JOIN courses c ON e.course_id = c.id
    WHERE c.price > 0
");
$revenue = $revStmt->fetch()['revenue'] ?? 0;

// List all users
$users = $pdo->query("SELECT id, name, email, role FROM users")->fetchAll();

// List all courses
$courses = $pdo->query("
    SELECT c.*, u.name AS instructor
    FROM courses c
    LEFT JOIN users u ON c.instructor_id = u.id
")->fetchAll();
?>

<h2>📊 Admin Dashboard</h2>

<p><strong>Total Users:</strong> <?= $userCount ?></p>
<p><strong>Total Courses:</strong> <?= $courseCount ?></p>
<p><strong>Total Revenue:</strong> ₹<?= number_format($revenue, 2) ?></p>

<hr>

<h3>👥 All Users</h3>
<table border="1" cellpadding="5">
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['name']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= $u['role'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<hr>

<h3>📚 All Courses</h3>
<table border="1" cellpadding="5">
    <tr><th>ID</th><th>Title</th><th>Instructor</th><th>Price</th></tr>
    <?php foreach ($courses as $c): ?>
    <tr>
        <td><?= $c['id'] ?></td>
        <td><?= htmlspecialchars($c['title']) ?></td>
        <td><?= htmlspecialchars($c['instructor']) ?></td>
        <td>₹<?= $c['price'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="index.php?page=home">🏠 Back to Home</a>
