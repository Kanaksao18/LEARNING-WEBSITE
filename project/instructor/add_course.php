<?php
require_once "includes/db.php";
require_once "includes/functions.php";
session_start();

if (!isLoggedIn() || $_SESSION['role'] !== 'instructor') {
    header("Location: ../index.php?page=login");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);

    if ($title && $description) {
        $stmt = $pdo->prepare("INSERT INTO courses (title, description, price, instructor_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $price, $_SESSION['user_id']]);

        header("Location: ../index.php?page=instructor");
        exit;
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-white p-6">
    <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 rounded p-6 shadow">
        <h2 class="text-2xl font-bold mb-4">➕ Add New Course</h2>

        <?php if ($error): ?>
            <div class="text-red-600 mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1">Title</label>
                <input type="text" name="title" required class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600">
            </div>

            <div>
                <label class="block mb-1">Description</label>
                <textarea name="description" required class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600"></textarea>
            </div>

            <div>
                <label class="block mb-1">Price (₹)</label>
                <input type="number" name="price" step="0.01" min="0" class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
                Create Course
            </button>
        </form>
    </div>
</body>
</html>
