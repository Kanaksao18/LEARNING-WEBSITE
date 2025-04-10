<?php
if (!isLoggedIn() || $_SESSION['role'] !== 'instructor') {
    header("Location: index.php?page=login");
    exit;
}

$course_id = $_GET['course_id'] ?? null;

// Validate ownership
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ? AND instructor_id = ?");
$stmt->execute([$course_id, $_SESSION['user_id']]);
$course = $stmt->fetch();

if (!$course) {
    echo "Unauthorized or course not found.";
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $video = $_FILES['video'];

    if ($title && $video['tmp_name']) {
        $videoName = uniqid() . "_" . basename($video['name']);
        $targetPath = "uploads/videos/" . $videoName;
        move_uploaded_file($video['tmp_name'], $targetPath);

        $stmt = $pdo->prepare("INSERT INTO course_videos (course_id, title, video_path) VALUES (?, ?, ?)");
        $stmt->execute([$course_id, $title, $targetPath]);

        echo "<p>✅ Video uploaded successfully!</p>";
    } else {
        $error = "All fields required.";
    }
}
?>

<h2>📤 Upload Video to: <?= htmlspecialchars($course['title']) ?></h2>
<?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Video Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Choose Video File:</label><br>
    <input type="file" name="video" accept="video/*" required><br><br>

    <button type="submit">Upload</button>
</form>
