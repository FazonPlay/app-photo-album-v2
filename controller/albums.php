<?php
/**
 * @var PDO $pdo
 */
registerCss("assets/css/dashboard.css");
require "model/albums.php";
require "model/album.php";
require "model/photos.php";

$users = getAllUsers($pdo);

$userId = $_SESSION['user_id'] ?? 0;

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            $albumId = intval($_POST['id']);
            $album = getAlbum($pdo, $albumId);
            if (isAdmin() || isAlbumOwner($album, $userId)) {
                $result = deleteAlbum($pdo, $albumId);
                if ($result === true) {
                    logDeletion('album', $albumId, 'album');
                }
                header('Content-Type: application/json');
                echo json_encode(['success' => $result === true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No permission']);
            }
            exit();

        } elseif (isset($_POST['action']) && $_POST['action'] === 'invite') {
            $albumId = intval($_POST['album_id']);
            $recipientEmail = $_POST['email'];
            $permission = $_POST['permission'];
            $message = $_POST['message'] ?? '';
            $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));
            $token = inviteUserToAlbum($pdo, $albumId, $userId, $recipientEmail, $permission, $message, $expiresAt);
            echo json_encode(['success' => true, 'token' => $token]);
            exit();
        }

    } else {
        $selectedUserId = $_GET['user_id'] ?? null;
        $page = intval($_GET['page'] ?? 1);
        $tag = $_GET['tag'] ?? '';
        $title = $_GET['title'] ?? '';
        if (isAdmin()) {
            if ($selectedUserId === 'all' || $selectedUserId === null) {
                $result = getAlbums($pdo, $page, 20, null); // Admin viewing all users
            } else {
                $result = getAlbums($pdo, $page, 20, intval($selectedUserId)); // Admin viewing specific user
            }
        } else {
            // Non-admin can only view their own or shared albums
            $result = getAlbums($pdo, $page, 20, $userId);
        }



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