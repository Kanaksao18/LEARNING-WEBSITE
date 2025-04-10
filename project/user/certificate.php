<?php
if (!isLoggedIn()) {
    header("Location: index.php?page=login");
    exit;
}

$course_id = $_GET['course_id'] ?? null;

// Basic check (in production you'd store & verify quiz results)
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    echo "Course not found.";
    exit;
}

// Generate certificate HTML
$student_name = htmlspecialchars($_SESSION['name']);
$course_title = htmlspecialchars($course['title']);
$date = date("F d, Y");
?>

<style>
    .certificate {
        width: 80%;
        margin: auto;
        padding: 30px;
        border: 10px solid gold;
        text-align: center;
        font-family: 'Georgia', serif;
        background: #fdf6e3;
    }
    .certificate h1 { font-size: 40px; margin-bottom: 0; }
    .certificate h2 { font-size: 28px; margin-top: 0; }
    .certificate p { font-size: 18px; }
</style>

<div class="certificate">
    <h1>Certificate of Completion</h1>
    <p>This certifies that</p>
    <h2><?= $student_name ?></h2>
    <p>has successfully completed the course</p>
    <h2><?= $course_title ?></h2>
    <p>on <?= $date ?></p>
    <br><br>
    <p><strong>Instructor:</strong> <?= getInstructorName($pdo, $course['instructor_id']) ?></p>
</div>

<a href="index.php?page=dashboard">Back to Dashboard</a>
