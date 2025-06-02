<?php
/**
 * @var PDO $pdo
 */
registerCss("assets/css/dashboard.css");
registerCss("assets/css/user.css");
require "model/user.php";
$action = 'create';
$errors = [];
if (isset($_POST['create_button'])) {
    $username = cleanString($_POST['username'] ?? '');
    $email = cleanString($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';
    $roles = $_POST['roles'] ?? 'user';
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($password !== $confirmation) {
        $errors[] = "The passwords do not match";
    } elseif (!$username || !$email || !$roles) {
        $errors[] = "All fields are required";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $result = insertUser($pdo, $username, $email, $password_hash, $roles, $is_active);
        if ($result !== true) {
            $errors[] = $result;
        }
    }
}
require "view/user.php";