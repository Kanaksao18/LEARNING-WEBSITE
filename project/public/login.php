<?php
require_once "includes/db.php";
require_once "includes/functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = getUserByEmail($pdo, $email);
    if ($user && password_verify($password, $user['password'])) {
        loginUser($user);

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } elseif ($user['role'] === 'instructor') {
            header("Location: instructor/dashboard.php");
        } else {
            header("Location: index.php?page=dashboard");
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Login - EduHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-white flex items-center justify-center min-h-screen">

    <div class="bg-white dark:bg-gray-800 p-8 rounded shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">🔐 Login to EduHub</h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" required class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600">
            </div>

            <div>
                <label class="block mb-1" for="password">Password</label>
                <input type="password" name="password" id="password" required class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                Login
            </button>
        </form>

        <p class="text-center mt-4">
            Don't have an account? <a href="index.php?page=register" class="text-blue-500 hover:underline">Register here</a>
        </p>
    </div>

</body>
</html>