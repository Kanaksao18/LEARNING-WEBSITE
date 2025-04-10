<?php
// index.php - Entry point of the Online Course Marketplace

session_start();
require_once "includes/db.php";
require_once "includes/functions.php";

// Simple router logic
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        include "public/home.php";
        break;
    case 'login':
        include "public/login.php";
        break;
    case 'register':
        include "public/register.php";
        break;
    case 'logout':
        session_destroy();
        header("Location: index.php?page=home");
        break;
    case 'dashboard':
        include "user/dashboard.php";
        break;
    case 'instructor':
        include "instructor/dashboard.php";
        break;
    case 'course':
        include "public/course.php";
        break;
    case 'enroll':
        include "user/enroll.php";
        break;
    case 'admin':
        include "admin/dashboard.php";
        break;
    case 'instructor_videos':
        include "instructor/videos.php";
        break;
    case 'course':
        include "public/course.php";
        break;
    case 'instructor_quiz':
        include "instructor/quiz.php";
        break;
    case 'take_quiz':
            include "user/take_quiz.php";
            break;
    case 'certificate':
        include "user/certificate.php";
        break;
    case 'payment':
        include "user/payment.php";
         break;
    case 'add_course':
        include "instructor/add_course.php";
        break; 
    case 'admin':
        include "admin/dashboard.php";
        break;
        case 'upload_video':
            include "instructor/upload_video.php";
            break;
                                         
    default:
        echo "Page not found.";
}
?>
