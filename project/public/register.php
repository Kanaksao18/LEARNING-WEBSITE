<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Register - EduHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-white flex items-center justify-center min-h-screen">

    <div class="bg-white dark:bg-gray-800 p-8 rounded shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">📝 Register to EduHub</h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1" for="name">Full Name</label>
                <input type="text" name="name" id="name" required class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600">
            </div>

            <div>
                <label class="block mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" required class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600">
            </div>

            <div>
                <label class="block mb-1" for="password">Password</label>
                <input type="password" name="password" id="password" required class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600">
            </div>

            <div>
                <label class="block mb-1" for="role">Register As</label>
                <select name="role" id="role" class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600">
                    <option value="student">Student</option>
                    <option value="instructor">Instructor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                Register
            </button>
        </form>

        <p class="text-center mt-4">
            Already have an account? <a href="index.php?page=login" class="text-blue-500 hover:underline">Login here</a>
        </p>
    </div>

</body>
</html>
