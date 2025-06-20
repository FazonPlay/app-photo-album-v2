<?php
// controller/albums_public.php
/**
 * @var PDO $pdo
 */
registerCss("assets/css/dashboard.css");
require "model/albums_public.php";
require "model/photos.php";

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    $page = intval($_GET['page'] ?? 1);
    $result = getPublicAlbums($pdo, $page, 20);

    if (is_array($result)) {
        header('Content-Type: application/json');
        echo json_encode(['results' => $result['albums'], 'count' => $result['total']]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['errors' => [$result]]);
    }
    exit();
}

require "view/albums_public.php";