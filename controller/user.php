<?php
/**
 * @var PDO $pdo
 */
require "model/user.php";
$action = 'create';
$errors = [];
if (!empty($_GET['id'])) {
    $action = 'edit';
    $user = getUser($pdo, $_GET['id']);
    if(!is_array($user)) {
        $errors = $user;
    }
}

if (isset($_POST['create_button'])) {
    $username = !empty($_POST['username']) ? cleanString($_POST['username']) : null;
    $password = !empty($_POST['password']) ? cleanString($_POST['password']) : null;
    $confirmation = !empty($_POST['confirmation']) ? cleanString($_POST['confirmation']) : null;
    if ($password !== $confirmation) {
        $errors[] = "The passwords do not match";
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $newUser = insertUser($pdo, $username, $password );
        if (!is_bool($newUser)) {
            $errors[] = $newUser;
        }
    }
}

if (isset($_POST['edit_button'])) {
    $id = cleanString($_GET['id']);
    $username = !empty($_POST['username']) ? cleanString($_POST['username']) : null;
    $password = !empty($_POST['password']) ? cleanString($_POST['password']) : null;
    $confirmation = !empty($_POST['confirmation']) ? cleanString($_POST['confirmation']) : null;
    if (!empty($password) && !empty($confirmation) && ($password === $confirmation)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
    } elseif(!empty($password) && !empty($confirmation) && ($password !== $confirmation)) {
        $errors[] = "The passwords do not match";
    }
    $res = updateUser($pdo, $id, $username, $password);
}

require "view/user.php";