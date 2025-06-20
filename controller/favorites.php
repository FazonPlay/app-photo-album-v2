<?php
/**
 * @var PDO $pdo
 */
registerCss("assets/css/photos.css");
registerCss("assets/css/dashboard.css");
require "model/favorites.php";

$errors = [];
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    $page = intval($_GET['page'] ?? 1);
    $userId = $_SESSION['user_id'] ?? 0;
    $result = getFavoritePhotos($pdo, $userId, $page, 20);

    if (is_array($result)) {
        header('Content-Type: application/json');
        echo json_encode(['results' => $result['photos'], 'count' => $result['total']]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['errors' => [$result]]);
    }
    exit();
}

require "view/favorites.php";