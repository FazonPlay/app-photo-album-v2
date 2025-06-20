<?php
/**
 * @var PDO $pdo
 */
require "model/profile.php";
registerCss("assets/css/dashboard.css");
registerCss("assets/css/profile.css");

if (!isset($_SESSION['auth']) || !isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Access Denied: You must be logged in to view your profile.</div>';
    exit;
}

$errors = [];
$success = false;
$user_id = $_SESSION['user_id'];

$profileData = getUserProfile($pdo, $user_id);

if (is_string($profileData)) {
    $errors[] = $profileData;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';

    if ($username === '') $errors[] = "Username is required.";
    if ($email === '') $errors[] = "Email is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";

    if ($password !== '') {
        if ($confirmation === '') $errors[] = "Password confirmation is required.";
        if ($password !== $confirmation) $errors[] = "Passwords do not match.";
    }

    // Profile picture upload handling
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['profile_picture']['type'];

        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Invalid file type. Only JPG, PNG, and GIF files are allowed.";
        } else {
            $maxFileSize = 2 * 1024 * 1024; // 2MB
            if ($_FILES['profile_picture']['size'] > $maxFileSize) {
                $errors[] = "File is too large. Maximum size is 2MB.";
            } else {
                $uploadDir = 'uploads/profile_pictures/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = uniqid('profile_') . '_' . basename($_FILES['profile_picture']['name']);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filePath)) {
                    $profile_picture = $filePath;
                } else {
                    $errors[] = "Failed to upload profile picture.";
                }
            }
        }
    }

    // Update profile if no errors
    if (empty($errors)) {
        $updateData = [
            'username' => $username,
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'bio' => $bio
        ];

        if ($password !== '') {
            $updateData['password'] = $password;
        }

        if ($profile_picture) {
            $updateData['profile_picture'] = $profile_picture;
        }

        $result = updateUserProfile($pdo, $user_id, $updateData);

        if ($result === true) {
            $success = true;
            // Update profile data after successful update
            $profileData = getUserProfile($pdo, $user_id);

            if ($username !== $_SESSION['username']) {
                // Update session username if changed
                $_SESSION['username'] = $username;
            }

        } else {
            $errors[] = $result;
        }
    }
}

require "view/profile.php";