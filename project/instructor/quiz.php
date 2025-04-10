<?php
if (!isLoggedIn() || !isInstructor()) {
    header("Location: index.php?page=login");
    exit;
}

$course_id = $_GET['course_id'] ?? null;
if (!$course_id) {
    echo "Course ID missing";
    exit;
}

// Check/create quiz
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE course_id = ?");
$stmt->execute([$course_id]);
$quiz = $stmt->fetch();

if (!$quiz) {
    $stmt = $pdo->prepare("INSERT INTO quizzes (course_id, title) VALUES (?, 'Course Quiz')");
    $stmt->execute([$course_id]);
    $quiz_id = $pdo->lastInsertId();
} else {
    $quiz_id = $quiz['id'];
}

// Add question
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $q = $_POST['question'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];
    $correct = $_POST['correct_option'];

    $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question, option_a, option_b, option_c, option_d, correct_option)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$quiz_id, $q, $a, $b, $c, $d, $correct]);
}

// Fetch questions
$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();
?>

<h2>Manage Quiz Questions</h2>
<form method="POST">
    <textarea name="question" placeholder="Enter question" required></textarea><br>
    <input type="text" name="option_a" placeholder="Option A" required><br>
    <input type="text" name="option_b" placeholder="Option B" required><br>
    <input type="text" name="option_c" placeholder="Option C" required><br>
    <input type="text" name="option_d" placeholder="Option D" required><br>
    <select name="correct_option">
        <option value="A">Correct: A</option>
        <option value="B">Correct: B</option>
        <option value="C">Correct: C</option>
        <option value="D">Correct: D</option>
    </select><br>
    <button type="submit">Add Question</button>
</form>

<h3>Questions</h3>
<?php foreach ($questions as $q): ?>
    <p><strong><?= htmlspecialchars($q['question']) ?></strong><br>
    A. <?= $q['option_a'] ?><br>
    B. <?= $q['option_b'] ?><br>
    C. <?= $q['option_c'] ?><br>
    D. <?= $q['option_d'] ?><br>
    ✅ Correct: <?= $q['correct_option'] ?></p>
<?php endforeach; ?>
