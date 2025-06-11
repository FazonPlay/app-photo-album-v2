<?php
registerCss("assets/css/dashboard.css");
require "model/albums.php";


if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            $albumId = intval($_POST['id']);
            $result = deleteAlbum($pdo, $albumId);
            header('Content-Type: application/json');
            echo json_encode(['success' => $result === true]);
            exit();
        } elseif (isset($_POST['action']) && $_POST['action'] === 'toggle_favorite') {
            $albumId = intval($_POST['id']);
            $userId = $_SESSION['user_id'] ?? 0;
            $result = toggleAlbumFavorite($pdo, $albumId, $userId);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }
    } else {
        $page = intval($_GET['page'] ?? 1);
        $result = getAlbums($pdo, $page, 20);
        if (is_array($result)) {
            header('Content-Type: application/json');
            echo json_encode(['results' => $result['albums'], 'count' => $result['total']]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['errors' => [$result]]);
        }
        exit();
    }
}

require "view/albums.php";