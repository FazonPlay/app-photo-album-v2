<?php
/**
 * @var PDO $pdo
 */
require_once "model/user.php";
registerCss("assets/css/dashboard.css");
registerCss("assets/css/user.css");

$errors = [];
$action = 'create';
$userData = [
    'user_id' => '',
    'username' => '',
    'email' => '',
    'roles' => 'user',
    'is_active' => 1
];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $action = 'edit';
    $userId = (int)$_GET['id'];
    $userData = getUser($pdo, $userId);
    if (!$userData) {
        $errors[] = "User not found.";
        $userData = [
            'user_id' => '',
            'username' => '',
            'email' => '',
            'roles' => 'user',
            'is_active' => 1
        ];
        $action = 'create';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $roles = $_POST['roles'] ?? 'user';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $password = $_POST['password'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';

    // Validation
    if ($username === '') $errors[] = "Username is required.";
    if ($email === '') $errors[] = "Email is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if ($roles === '') $errors[] = "Role is required.";

    if ($action === 'create') {
        if ($password === '') $errors[] = "Password is required.";
        if ($confirmation === '') $errors[] = "Password confirmation is required.";
        if ($password !== $confirmation) $errors[] = "Passwords do not match.";
    } elseif ($action === 'edit' && $password !== '') {
        if ($password !== $confirmation) $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        if ($action === 'create') {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $result = insertUser($pdo, $username, $email, $password_hash, $roles, $is_active);
            if ($result === true) {
                $userId = $pdo->lastInsertId();
                logCreation('user', $userId, $username, [
                    'email' => $email,
                    'role' => $roles
                ]);
            }            if ($result === true) {
//                logRegistration($userId, $username); doesnt work atm
                header("Location: index.php?component=users");
                exit;
            } else {
                $errors[] = $result;
            }
        } elseif ($action === 'edit') {
            $userId = (int)($_POST['user_id'] ?? 0);
            $password_hash = $password !== '' ? password_hash($password, PASSWORD_BCRYPT) : null;
            $result = updateUser($pdo, $userId, $username, $password_hash);
            if ($result === true) {
                // Optionally update email, roles, is_active
                $query = "UPDATE users SET email = :email, roles = :roles, is_active = :is_active WHERE user_id = :id";
                $prep = $pdo->prepare($query);
                $prep->bindValue(':email', $email);
                $prep->bindValue(':roles', $roles);
                $prep->bindValue(':is_active', $is_active, PDO::PARAM_INT);
                $prep->bindValue(':id', $userId, PDO::PARAM_INT);
                $prep->execute();
                logUpdate('user', $userId, $username);

                header("Location: index.php?component=users");
                exit;
            } else {
                $errors[] = $result;
            }
        }
    }
    $userData = [
        'user_id' => $_POST['user_id'] ?? '',
        'username' => $username,
        'email' => $email,
        'roles' => $roles,
        'is_active' => $is_active
    ];
}

require "view/user.php";