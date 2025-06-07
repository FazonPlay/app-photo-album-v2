<?php
registerCss("assets/css/photos.css");
registerCss("assets/css/dashboard.css");
require "model/photo.php";

$users = getAllUsers($pdo);

$errors = [];
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            $photoId = intval($_POST['id']);
            $result = deletePhoto($pdo, $photoId);
            header('Content-Type: application/json');
            echo json_encode(['success' => $result === true]);
            exit();
        } elseif (isset($_POST['action']) && $_POST['action'] === 'add') {
            $title = $_POST['title'] ?? '';
            $userId = $_SESSION['user_id'] ?? 0;
            // Handle file upload
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/photos/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $filename = uniqid() . '_' . basename($_FILES['photo']['name']);
                $filePath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
                    $result = addPhoto($pdo, $title, $filePath, $userId);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => $result === true]);
                    exit();
                } else {
                    $errors[] = "Failed to upload file.";
                }
            } else {
                $errors[] = "No file uploaded.";
            }
            header('Content-Type: application/json');
            echo json_encode(['errors' => $errors]);
            exit();
        } elseif (isset($_POST['action']) && $_POST['action'] === 'toggle_favorite') {
            $photoId = intval($_POST['id']);
            $userId = $_SESSION['user_id'] ?? 0;
            $result = toggleFavorite($pdo, $photoId, $userId);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }
    } else {
        $page = intval($_GET['page'] ?? 1);
        $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
        $favorites = isset($_GET['favorites']) && $_GET['favorites'] == 1;

        if ($favorites && isset($_SESSION['user_id'])) {
            $result = getFavoritePhotos($pdo, $_SESSION['user_id'], $page, 20);
        } elseif ($userId) {
            $result = getPhotosByUser($pdo, $userId, $page, 20);
        } else {
            $result = getPhotos($pdo, $page, 20);
        }

        if (is_array($result)) {
            header('Content-Type: application/json');
            echo json_encode(['results' => $result['photos'], 'count' => $result['total']]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['errors' => [$result]]);
        }
        exit();
    }
}

require "view/photo.php";