<?php
if (!isLoggedIn() || !isInstructor()) {
    header("Location: index.php?page=login");
    exit;
}

$course_id = $_GET['course_id'] ?? null;

if (!$course_id) {
    echo "Course ID missing.";
    exit;
}

// Handle video upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $video_url = $_POST['video_url']; // Simplified (ideally use file upload)

    $stmt = $pdo->prepare("INSERT INTO videos (course_id, title, video_url) VALUES (?, ?, ?)");
    $stmt->execute([$course_id, $title, $video_url]);
}

// Fetch videos
$stmt = $pdo->prepare("SELECT * FROM videos WHERE course_id = ?");
$stmt->execute([$course_id]);
$videos = $stmt->fetchAll();
?>

<h2>Manage Videos</h2>
<form method="POST">
    <input type="text" name="title" placeholder="Video Title" required><br>
    <input type="text" name="video_url" placeholder="Video URL (YouTube, Vimeo, etc.)" required><br>
    <button type="submit">Add Video</button>
</form>

<h3>Course Videos</h3>
<?php foreach ($videos as $video): ?>
    <div style="margin: 10px;">
        <strong><?= htmlspecialchars($video['title']) ?></strong> — <?= htmlspecialchars($video['video_url']) ?>
    </div>
<?php endforeach; ?>

<a href="index.php?page=instructor">Back to Dashboard</a>
<a href="index.php?page=instructor_quiz&course_id=<?= $course_id ?>">Manage Quiz</a>

