<?php
/**
 * @var PDO $pdo
 */
require "model/login.php";
registerCss("assets/css/create_user.css");


if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
) {
    error_log('POST data: ' . print_r($_POST, true));

    $errors = [];
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if (null === $email || null === $password) {
        $errors[] = "The login or password is missing";
    } else {
        $user = login($pdo, $email);

        if (!$user) {
            $errors[] = "User not found";
        }
        elseif (!password_verify($password, $user['password_hash'])) { // use password_hash instead of password
                $errors[] = "Invalid password";
            } else {
            $_SESSION["auth"] = true;
            $_SESSION["username"] = $user['username'];
            $_SESSION["role"] = $user['roles'];
            $_SESSION["is_admin"] = ($user['roles'] === 'admin');
            $_SESSION['user_id'] = $user['user_id']; // CORRECT - match database column name
            header("Content-Type: application/json");
            echo json_encode(['authentication' => true]);
            exit();
        }
    }

    if (!empty($errors)) {
        header("Content-Type: application/json");
        echo json_encode(['errors' => $errors]);
        exit();
    }
}

require "view/login.php";
