<?php
if (!isLoggedIn()) {
    header("Location: index.php?page=login");
    exit;
}

$course_id = $_GET['course_id'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if (!$course) {
    echo "Course not found.";
    exit;
}

// Simulate payment (you can integrate Razorpay/Stripe here)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Payment successful (simulation)
    $stmt = $pdo->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $course_id]);

    echo "<h2>🎉 Payment Successful</h2>";
    echo "<p>You have been enrolled in <strong>{$course['title']}</strong>.</p>";
    echo "<a href='index.php?page=course&id=$course_id'>Go to Course</a>";
    exit;
}
?>

<h2>Pay for: <?= htmlspecialchars($course['title']) ?></h2>
<p><strong>Amount:</strong> ₹<?= $course['price'] ?></p>

<form method="POST">
    <p><strong>Simulating payment...</strong></p>
    <button type="submit">Confirm & Pay</button>
</form>
