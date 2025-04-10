<?php
require_once "includes/db.php";
require_once "includes/functions.php";
session_start();

if (!isLoggedIn() || !isStudent()) {
    header("Location: index.php?page=login");
    exit;
}

$courses = getCourses($pdo);

// Fetch enrolled course IDs
$stmt = $pdo->prepare("SELECT course_id FROM enrollments WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$enrolledIds = array_column($stmt->fetchAll(), 'course_id');

// Fetch quizzes for enrolled courses
$quizzes = [];
if (!empty($enrolledIds)) {
    $placeholders = implode(',', array_fill(0, count($enrolledIds), '?'));
    $stmt = $pdo->prepare("SELECT q.*, c.title AS course_title FROM quizzes q JOIN courses c ON q.course_id = c.id WHERE q.course_id IN ($placeholders)");
    $stmt->execute($enrolledIds);
    $quizzes = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-white min-h-screen">

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-3xl font-bold">👋 Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h2>
            <a href="index.php?page=logout" class="text-red-500 hover:underline">Logout</a>
        </div>

        <h3 class="text-2xl font-semibold mb-4">📚 Available Courses</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($courses as $course): ?>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow">
                    <h4 class="text-xl font-semibold mb-2"><?= htmlspecialchars($course['title']) ?></h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2"><?= htmlspecialchars($course['description']) ?></p>
                    <p class="text-sm"><strong>👨‍🏫 Instructor:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p>
                    <p class="text-sm mb-4"><strong>💰 Price:</strong> ₹<?= $course['price'] ?></p>
                    
                    <?php if (in_array($course['id'], $enrolledIds)): ?>
                        <a href="index.php?page=course&id=<?= $course['id'] ?>" class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                            View Course
                        </a>
                    <?php else: ?>
                        <a href="index.php?page=enroll&id=<?= $course['id'] ?>" class="block text-center bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded">
                            Enroll Now
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <hr class="my-8">

        <h3 class="text-2xl font-semibold mb-4">📝 Available Quizzes</h3>

        <?php if (!empty($quizzes)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow">
                        <h4 class="text-xl font-semibold mb-2"><?= htmlspecialchars($quiz['title']) ?></h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2"><strong>📚 Course:</strong> <?= htmlspecialchars($quiz['course_title']) ?></p>
                        <a href="take_quiz.php?course_id=<?= $quiz['course_id'] ?>" class="block text-center bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded">
                            Take Quiz
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600 dark:text-gray-300">No quizzes available for your enrolled courses.</p>
        <?php endif; ?>
    </div>

</body>
</html>