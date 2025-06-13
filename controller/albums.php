<?php
/**
 * @var PDO $pdo
 */
registerCss("assets/css/dashboard.css");
require "model/albums.php";

$userId = $_SESSION['user_id'] ?? 0;

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['action']) && $_POST['action'] === 'delete') {
            $albumId = intval($_POST['id']);
            $album = getAlbum($pdo, $albumId);
            if (isAdmin() || isAlbumOwner($album, $userId)) {
                $result = deleteAlbum($pdo, $albumId);
                header('Content-Type: application/json');
                echo json_encode(['success' => $result === true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No permission']);
            }
            exit();

        } elseif (isset($_POST['action']) && $_POST['action'] === 'toggle_favorite') {
            $albumId = intval($_POST['id']);
            $userId = $_SESSION['user_id'] ?? 0;
            $result = toggleAlbumFavorite($pdo, $albumId, $userId);
            header('Content-Type: application/json');
            echo json_encode($result);
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
        // Search/filter
        $page = intval($_GET['page'] ?? 1);
        $tag = $_GET['tag'] ?? '';
        $title = $_GET['title'] ?? '';
        if ($tag) {
            $result = getAlbumsByTag($pdo, $userId, $tag, $page, 20);
        } elseif ($title) {
            // Filter by title, only own or accessible
            $query = "SELECT a.*, p.thumbnail_path, p.file_path,
                CASE WHEN p.thumbnail_path IS NOT NULL AND p.thumbnail_path != '' THEN p.thumbnail_path ELSE p.file_path END AS cover_path
                FROM albums a
                LEFT JOIN photos p ON a.cover_photo_id = p.photo_id
                WHERE (a.user_id = :user_id OR a.visibility = 'public' OR EXISTS (SELECT 1 FROM album_access aa WHERE aa.album_id = a.album_id AND aa.user_id = :user_id))
                AND a.title LIKE :title
                ORDER BY a.creation_date DESC
                LIMIT 20 OFFSET :offset";

            // now i really dont like having sql queries in the controller, doesnt even work :sad:
            // the } else { part needs to get fixed and moved to the model
            $prep = $pdo->prepare($query);
            $prep->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $prep->bindValue(':title', "%$title%");
            $prep->bindValue(':offset', ($page-1)*20, PDO::PARAM_INT);
            $prep->execute();
            $albums = $prep->fetchAll(PDO::FETCH_ASSOC);
            // Count
            $countPrep = $pdo->prepare("SELECT COUNT(*) AS total FROM albums a WHERE (a.user_id = :user_id OR a.visibility = 'public' OR EXISTS (SELECT 1 FROM album_access aa WHERE aa.album_id = a.album_id AND aa.user_id = :user_id)) AND a.title LIKE :title");
            $countPrep->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $countPrep->bindValue(':title', "%$title%");
            $countPrep->execute();
            $count = $countPrep->fetch(PDO::FETCH_ASSOC);
            $result = ['albums' => $albums, 'total' => $count['total']];
        } else {
            $result = getAlbums($pdo, $page, 20);
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