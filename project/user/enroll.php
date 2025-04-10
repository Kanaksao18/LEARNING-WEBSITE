<?php
if (!isLoggedIn() || !isStudent()) {
    header("Location: index.php?page=login");
    exit;
}

$courseId = $_GET['id'] ?? null;

if ($courseId) {
    // Check if already enrolled
    $stmt = $pdo->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$_SESSION['user_id'], $courseId]);
    $already = $stmt->fetch();

    if (!$already) {
        // Insert enrollment
        $stmt = $pdo->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $courseId]);
    }

    header("Location: index.php?page=dashboard");
    exit;
}
