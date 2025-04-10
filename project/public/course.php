<?php
if (!isLoggedIn()) {
    header("Location: index.php?page=login");
    exit;
}

$course_id = $_GET['id'] ?? null;
if (!$course_id) {
    echo "Course not found.";
    exit;
}

// Check if user is enrolled
$stmt = $pdo->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
$stmt->execute([$_SESSION['user_id'], $course_id]);
$isEnrolled = $stmt->fetch();

if (!$isEnrolled && !isInstructor() && !isAdmin()) {
    echo "Access denied. Please enroll to view this course.";
    exit;
}

// Get course info
$course = getCourse($pdo, $course_id);

// Get course videos
$stmt = $pdo->prepare("SELECT * FROM videos WHERE course_id = ?");
$stmt->execute([$course_id]);
$videos = $stmt->fetchAll();
?>

<h2><?= htmlspecialchars($course['title']) ?></h2>
<p><?= htmlspecialchars($course['description']) ?></p>
<p><strong>Price:</strong> ₹<?= $course['price'] ?></p>

<h3>Video Lessons</h3>
<?php if (count($videos) === 0): ?>
    <p>No videos added yet.</p>
<?php else: ?>
    <?php foreach ($videos as $video): ?>
        <div style="border:1px solid #ccc; margin:10px; padding:10px;">
            <h4><?= htmlspecialchars($video['title']) ?></h4>
            <iframe width="560" height="315" src="<?= htmlspecialchars($video['video_url']) ?>" 
                frameborder="0" allowfullscreen></iframe>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<a href="index.php?page=dashboard">Back to Dashboard</a>
<a href="index.php?page=take_quiz&course_id=<?= $course['id'] ?>">Take Quiz</a>

<?php
$isEnrolled = false;
if (isLoggedIn()) {
    $stmt = $pdo->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$_SESSION['user_id'], $course['id']]);
    $isEnrolled = $stmt->fetch();
}
?>

<h3>Enroll</h3>
<?php if (!$isEnrolled): ?>
    <?php if ($course['price'] > 0): ?>
        <p><strong>Price:</strong> ₹<?= $course['price'] ?></p>
        <a href="index.php?page=payment&course_id=<?= $course['id'] ?>">Pay Now & Enroll</a>
    <?php else: ?>
        <a href="index.php?page=enroll&course_id=<?= $course['id'] ?>">Enroll for Free</a>
    <?php endif; ?>
<?php else: ?>
    <p>✅ You are already enrolled in this course.</p>
<?php endif; ?>

<?php if ($isEnrolled): ?>
    <hr>
    <h3>🎬 Course Videos</h3>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM course_videos WHERE course_id = ?");
    $stmt->execute([$course['id']]);
    $videos = $stmt->fetchAll();
    ?>
    <?php if ($videos): ?>
        <ul>
        <?php foreach ($videos as $video): ?>
            <li style="margin-bottom: 20px;">
                <strong><?= htmlspecialchars($video['title']) ?></strong><br>
                <video width="500" controls>
                    <source src="<?= $video['video_path'] ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No videos uploaded yet.</p>
    <?php endif; ?>
<?php endif; ?>
