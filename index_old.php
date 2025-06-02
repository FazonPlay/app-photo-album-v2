<?php
//session_start();
//require __DIR__ . "/vendor/autoload.php";
//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
//$dotenv->safeLoad();
//require 'includes/database.php';
//require ("includes/helper.php");
//
//$customCssFiles = [];
//
//// Function to register CSS files
//function registerCss($cssFile) {
//    global $customCssFiles;
//    if (!in_array($cssFile, $customCssFiles)) {
//        $customCssFiles[] = $cssFile;
//    }
//}
//
//if (isset($_GET['disconnect'])) {
//    session_destroy();
//    header("Location: index.php");
//    exit();
//}
//if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
//    $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
//
//    $component = $_GET['component'] ?? null;
//
//    if (file_exists("controller/$component.php")) {
//        require "controller/$component.php";
//    } else {
//        throw new Exception("Component '$component' does not exist");
//    }
//    exit(); // Ensure we don't continue to the HTML output
//
//}
//$componentName = !empty($_GET['component'])
//    ? htmlspecialchars($_GET['component'], ENT_QUOTES, 'UTF-8')
//    : 'dashboard';
//?>
<!---->
<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <!--    <meta http-equiv="content-type" content="no-cache">-->-->
<!--    <title>Memory Game</title>-->
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">-->
<!--    <!-- In index.php's <head> section, after Bootstrap CSS -->-->
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">-->
<!--    <link rel="stylesheet" href="assets/css/style.css">-->
<!--    <!-- Custom CSS files -->-->
<!--    --><?php //foreach ($customCssFiles as $cssFile): ?>
<!--        <link rel="stylesheet" href="--><?php //echo htmlspecialchars($cssFile); ?><!--">-->
<!--    --><?php //endforeach; ?>
<!--</head>-->
<!--<body>-->
<!--<div class="container">-->
<!--    --><?php
//    $componentName = !empty($_GET['component'])
//        ? htmlspecialchars($_GET['component'], ENT_QUOTES, 'UTF-8') : 'dashboard';
//    require "_partials/navbar.php";
//
//    switch ($componentName) {
//        case 'login':
//            if (isset($_SESSION['auth'])) {
//                echo '<div class="alert alert-danger">Access Denied: You are already logged in.</div>';
//                require "controller/landing.php";
//                break;
//            }
//            require "controller/$componentName.php";
//            break;
//        case 'users': case 'user': case 'times': case 'admin':
//        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//            echo '<div class="alert alert-danger">Access Denied: Administrator privileges required.</div>';
//            require "controller/landing.php";
//            break;
//        }
//        require "controller/$componentName.php";
//        break;
//        case 'game':
//            if (!isset($_SESSION['auth'])) {
//                echo '<div class="alert alert-danger">Access Denied: You must be logged in to access your photos.</div>';
//                break;
//            }
//            require "controller/$componentName.php";
//            break;
//        default:
//            if (file_exists("controller/$componentName.php")) {
//                require "controller/$componentName.php";
//            } else {
//                throw new Exception("Component '$componentName' does not exist");
//            }
//    }
//    ?>
<!--</div>-->
<?php //require "_partials/_toast.html"; ?>
<?php //require "_partials/footer.php"; ?>
<!---->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>-->
<!--</body>-->
<!--</html>-->