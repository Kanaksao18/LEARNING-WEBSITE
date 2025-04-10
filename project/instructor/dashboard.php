<?php
require_once "includes/db.php";
require_once "includes/functions.php";
session_start();

if (!isLoggedIn() || $_SESSION['role'] !== 'instructor') {
    header("Location: ../index.php?page=login");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM courses WHERE instructor_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Instructor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-white min-h-screen">

    <div class="w-full bg-purple-800 dark:bg-purple-900 text-white py-4 px-6 flex justify-between items-center">
        <h1 class="text-xl font-bold">👨‍🏫 Instructor Dashboard</h1>
        <a href="../index.php?page=logout" class="text-sm underline hover:text-red-300">Logout</a>
    </div>

    <div class="max-w-6xl mx-auto p-4 sm:p-6">
        <div class="mb-6 text-center sm:text-left">
            <a href="add_course.php" class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-lg text-sm sm:text-base transition">➕ Create New Course</a>
        </div>

        <h2 class="text-2xl font-semibold mb-4">Your Courses</h2>

        <?php if ($courses): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($courses as $course): ?>
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow hover:shadow-lg transition-all">
                        <h3 class="text-xl font-bold mb-1"><?= htmlspecialchars($course['title']) ?></h3>
                        <p class="text-sm"><strong>Price:</strong> ₹<?= $course['price'] ?></p>
                        <p class="text-sm mb-3"><strong>Start Date:</strong> <?= $course['start_date'] ?></p>

                        <div class="flex flex-col sm:flex-row flex-wrap gap-2">
                            <a href="edit_course.php?id=<?= $course['id'] ?>" class="text-blue-600 hover:underline text-sm">✏️ Edit</a>
                            <a href="upload_video.php?course_id=<?= $course['id'] ?>" class="text-green-600 hover:underline text-sm">📹 Upload Videos</a>
                            <a href="add_quiz.php?course_id=<?= $course['id'] ?>" class="text-yellow-600 hover:underline text-sm">🧠 Add Quiz</a>
                            <a href="enrollments.php?course_id=<?= $course['id'] ?>" class="text-purple-600 hover:underline text-sm">📊 View Enrollments</a>
                            <a href="delete_course.php?id=<?= $course['id'] ?>" onclick="return confirm('Delete this course?')" class="text-red-600 hover:underline text-sm">🗑️ Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500">No courses found. Start by creating one.</p>
        <?php endif; ?>
    </div>
</body>
</html>
