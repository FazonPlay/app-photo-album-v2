<?php
/**
 * @var PDO $pdo
 */
registerCss("assets/css/dashboard.css");
require "model/shared.php";

$userId = $_SESSION['user_id'] ?? 0;
$sharedAlbums = getSharedAlbums($pdo, $userId);
require "view/shared.php";