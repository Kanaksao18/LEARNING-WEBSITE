<?php
// includes/functions.php

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isInstructor() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'instructor';
}

function isStudent() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'student';
}

function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];
}

function getCourses($pdo) {
    $stmt = $pdo->prepare("SELECT c.*, u.name as instructor_name 
                           FROM courses c 
                           JOIN users u ON c.instructor_id = u.id 
                           ORDER BY c.created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCourse($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getUserByEmail($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}
function getInstructorName($pdo, $instructor_id) {
    $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->execute([$instructor_id]);
    $row = $stmt->fetch();
    return $row ? $row['name'] : 'Instructor';
}

?>
