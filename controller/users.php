<?php
/**
 * @var PDO $pdo
 */
require "model/users.php";
require "model/create_user.php";
registerCss("./assets/css/admin.css");
registerCss("./assets/css/style.css");
registerCss("./assets/css/dashboard.css");



$is_admin = $_SESSION["is_admin"] === true;

const LIST_USERS_ITEMS_PER_PAGE = 10;

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && $is_admin) {
        $user_id = intval($_POST['id']);
        $delete_result = deleteUser($pdo, $user_id);
        if ($delete_result) {
            logDeletion('user', $user_id, 'user');
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => $delete_result]);
        exit();
    }

    $page = cleanString($_GET['page'] ?? '1');

    $result = getUsers($pdo, $page, LIST_USERS_ITEMS_PER_PAGE);

    if (is_array($result)) {
        $users = $result['users'];
        $count = $result['total'];
    } else {
        $errors[] = $result;
    }

    header('Content-Type: application/json');
    echo json_encode(['results' => $users, 'count' => $count]);
    exit();




}

require "view/users.php";