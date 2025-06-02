<?php
/**
 * @var PDO $pdo
 */
registerCss("assets/css/dashboard.css");
registerCss("assets/css/style.css");
registerCss("assets/css/admin.css");
require "model/admin.php";
// First, check if the user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit();
}

$totalUsers = getTotalUsers($pdo);
$totalAlbums = getTotalAlbums($pdo);
$totalPhotos = getTotalPhotos($pdo);
$diskUsage = getDiskUsage($pdo);

$recentUsers = getRecentUsers($pdo, 5);

$recentActivity = [];
require "view/admin.php";


