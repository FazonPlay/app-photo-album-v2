<?php
/**
 * @var PDO $pdo
 */
registerCss("assets/css/dashboard.css");
require "model/invitations.php";

$userId = $_SESSION['user_id'] ?? 0;

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['token'] ?? '';
        $action = $_POST['action'] ?? '';

        if (!empty($token)) {
            if ($action === 'accept') {
                $success = acceptAlbumInvitation($pdo, $token, $userId);
                echo json_encode(['success' => $success]);
            } elseif ($action === 'decline') {
                $success = declineAlbumInvitation($pdo, $token);
                echo json_encode(['success' => $success]);
            }
        }
        exit;
    }

    $invitations = getPendingInvitations($pdo, $userId);
    echo json_encode(['invitations' => $invitations]);
    exit;
}

$pendingInvitations = getPendingInvitations($pdo, $userId);
require "view/invitations.php";