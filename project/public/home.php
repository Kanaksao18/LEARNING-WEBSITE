<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduHub - Home</title>
    <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

<?php include "components/navbar.php"; ?>
<?php
require_once "includes/db.php";
require_once "includes/functions.php";

// Handle free enrollment
if (isset($_GET['action']) && $_GET['action'] === 'enroll' && isLoggedIn()) {
    $course_id = $_GET['course_id'];

    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch();

    if ($course) {
        if ($course['price'] == 0) {
            $check = $pdo->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
            $check->execute([$_SESSION['user_id'], $course_id]);
            if (!$check->fetch()) {
                $enroll = $pdo->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
                $enroll->execute([$_SESSION['user_id'], $course_id]);
                $message = "✅ Enrolled successfully!";
            }
        } else {
            header("Location: index.php?page=payment&course_id=" . $course_id);
            exit;
        }
    }
}

// Fetch all courses
$stmt = $pdo->query("
    SELECT c.*, u.name AS instructor_name
    FROM courses c
    JOIN users u ON c.instructor_id = u.id
    ORDER BY c.created_at DESC
");
$courses = $stmt->fetchAll();

// Get user name
$userName = '';
if (isLoggedIn()) {
    $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $userName = $user ? $user['name'] : 'User';
}
?>

<!-- ✅ Hero Section -->
<section class="text-center py-16 bg-gradient-to-br from-indigo-600 to-purple-600 text-white">
    <h1 class="text-4xl md:text-5xl font-bold mb-4">🎓 Welcome to EduHub</h1>
    <p class="text-lg md:text-xl mb-6">Learn from top instructors. Choose from free and paid courses across various categories.</p>
    
    <?php if (!isLoggedIn()): ?>
        <div class="space-x-4">
            <a href="index.php?page=login" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded hover:bg-gray-200 transition">🔐 Login</a>
            <a href="index.php?page=register" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded hover:bg-gray-200 transition">📝 Register</a>
        </div>
    <?php else: ?>
        <p class="text-lg">👋 Welcome, <strong><?= htmlspecialchars($userName) ?></strong>! 
            <a href="index.php?page=logout" class="underline text-gray-200 hover:text-white">Logout</a>
        </p>
    <?php endif; ?>
</section>

<!-- ✅ Flash Message -->
<?php if (!empty($message)): ?>
    <div class="max-w-3xl mx-auto my-6 bg-green-100 text-green-800 px-4 py-2 rounded shadow">
        <?= $message ?>
    </div>
<?php endif; ?>

<div class="bg-gray-100 dark:bg-gray-900 py-12 px-4 text-center">
    <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">📚 Select Your Grade</h2>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
        <?php
        // Example grades
        $grades = ['6th Grade', '7th Grade', '8th Grade', '9th Grade', '10th Grade'];
        foreach ($grades as $i => $grade): 
        ?>
            <a href="index.php?page=grade&grade=<?= urlencode($grade) ?>" class="bg-white dark:bg-gray-800 hover:bg-blue-100 dark:hover:bg-gray-700 p-6 rounded-lg shadow-md transition">
                <span class="text-xl font-semibold text-gray-800 dark:text-white"><?= htmlspecialchars($grade) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- ✅ Course Cards -->
<section class="max-w-7xl mx-auto px-4 py-10">
    <h2 class="text-2xl md:text-3xl font-bold mb-6 text-center">🔥 Latest Courses</h2>

    <?php if ($courses): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($courses as $course): ?>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
                    <h3 class="text-lg font-semibold mb-2 text-indigo-700 dark:text-indigo-300">
                        <?= htmlspecialchars($course['title']) ?>
                    </h3>
                    <p class="text-sm mb-1"><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p>
                    <p class="text-sm mb-4"><strong>Price:</strong> 
                        <?= $course['price'] > 0 ? "₹" . $course['price'] : "Free" ?>
                    </p>

                    <?php if (isLoggedIn()): ?>
                        <?php
                        $check = $pdo->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
                        $check->execute([$_SESSION['user_id'], $course['id']]);
                        $enrolled = $check->fetch();
                        ?>
                        <?php if ($enrolled): ?>
                            <a href="index.php?page=course&course_id=<?= $course['id'] ?>" class="text-indigo-600 dark:text-indigo-400 font-medium hover:underline">Go to Course</a>
                        <?php else: ?>
                            <?php if ($course['price'] > 0): ?>
                                <a href="index.php?page=payment&course_id=<?= $course['id'] ?>" class="text-blue-600 hover:underline">Pay & Enroll</a>
                            <?php else: ?>
                                <a href="index.php?page=home&action=enroll&course_id=<?= $course['id'] ?>" class="text-green-600 hover:underline">Enroll for Free</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-sm italic text-gray-500 dark:text-gray-400">Login to enroll</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-gray-500 dark:text-gray-400">No courses available right now. Check back soon!</p>
    <?php endif; ?>
</section>
<!-- ✅ Upcoming Courses -->
<section class="max-w-7xl mx-auto px-4 py-10">
    <h2 class="text-2xl md:text-3xl font-bold mb-6 text-center">📅 Upcoming Courses</h2>

    <?php
    $stmt = $pdo->prepare("
        SELECT c.*, u.name AS instructor_name
        FROM courses c
        JOIN users u ON c.instructor_id = u.id
        WHERE c.start_date > NOW()
        ORDER BY c.start_date ASC
    ");
    $stmt->execute();
    $upcomingCourses = $stmt->fetchAll();
    ?>

    <?php if ($upcomingCourses): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($upcomingCourses as $course): ?>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
                    <h3 class="text-lg font-semibold mb-2 text-purple-700 dark:text-purple-300">
                        <?= htmlspecialchars($course['title']) ?>
                    </h3>
                    <p class="text-sm mb-1"><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p>
                    <p class="text-sm mb-1"><strong>Starts On:</strong> <?= date('F j, Y', strtotime($course['start_date'])) ?></p>
                    <p class="text-sm mb-4"><strong>Price:</strong>
                        <?= $course['price'] > 0 ? "₹" . $course['price'] : "Free" ?>
                    </p>
                    <p class="text-sm italic text-gray-500 dark:text-gray-400">Enrollment opens soon</p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-gray-500 dark:text-gray-400">No upcoming courses at the moment.</p>
    <?php endif; ?>
</section>

<?php include "components/footer.php"; ?>

<!-- ✅ Dark Mode Toggle Script -->
<script>
  if (localStorage.getItem('theme') === 'dark') {
    document.documentElement.classList.add('dark');
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("toggleDark")?.addEventListener("click", () => {
      const html = document.documentElement;
      html.classList.toggle("dark");
      localStorage.setItem("theme", html.classList.contains("dark") ? "dark" : "light");
    });
  });
</script>

</body>
</html>
