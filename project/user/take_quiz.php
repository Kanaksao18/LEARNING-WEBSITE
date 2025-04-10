<?php
if (!isLoggedIn()) {
    header("Location: index.php?page=login");
    exit;
}

$course_id = $_GET['course_id'] ?? null;
if (!$course_id) {
    echo "Course ID missing";
    exit;
}

// Check if student is enrolled
$stmt = $pdo->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
$stmt->execute([$_SESSION['user_id'], $course_id]);
$isEnrolled = $stmt->fetch();

if (!$isEnrolled && !isInstructor()) {
    echo "You are not enrolled in this course.";
    exit;
}

// Get quiz
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE course_id = ?");
$stmt->execute([$course_id]);
$quiz = $stmt->fetch();

if (!$quiz) {
    echo "No quiz found for this course.";
    exit;
}

// Get questions
$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz['id']]);
$questions = $stmt->fetchAll();

// Handle submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    foreach ($questions as $q) {
        $qid = $q['id'];
        $correct = $q['correct_option'];
        $given = $_POST["q_$qid"] ?? '';
        if ($given === $correct) {
            $score++;
        }
    }
    $total = count($questions);
    echo "<h2>Quiz Completed</h2>";
    echo "<p>Your Score: $score / $total</p>";
    echo "<a href='index.php?page=course&id=$course_id'>Back to Course</a>";
    exit;
}
?>
<?php
// ... after calculating score
$pass_mark = ceil(0.6 * $total); // 60% to pass
$passed = $score >= $pass_mark;

// Store result (optional: create new table if needed)

echo "<h2>Quiz Completed</h2>";
echo "<p>Your Score: $score / $total</p>";

if ($passed) {
    echo "<p>✅ Congratulations! You passed the quiz.</p>";
    echo "<a href='index.php?page=certificate&course_id=$course_id'>Download Certificate</a><br>";
} else {
    echo "<p>❌ You did not pass. Try again!</p>";
}
echo "<a href='index.php?page=course&id=$course_id'>Back to Course</a>";
exit;
?>

<h2>Take Quiz: <?= htmlspecialchars($quiz['title']) ?></h2>
<form method="POST">
<?php foreach ($questions as $index => $q): ?>
    <p><strong>Q<?= $index + 1 ?>. <?= htmlspecialchars($q['question']) ?></strong></p>
    <label><input type="radio" name="q_<?= $q['id'] ?>" value="A"> A. <?= htmlspecialchars($q['option_a']) ?></label><br>
    <label><input type="radio" name="q_<?= $q['id'] ?>" value="B"> B. <?= htmlspecialchars($q['option_b']) ?></label><br>
    <label><input type="radio" name="q_<?= $q['id'] ?>" value="C"> C. <?= htmlspecialchars($q['option_c']) ?></label><br>
    <label><input type="radio" name="q_<?= $q['id'] ?>" value="D"> D. <?= htmlspecialchars($q['option_d']) ?></label><br><br>
<?php endforeach; ?>
    <button type="submit">Submit Quiz</button>
</form>
