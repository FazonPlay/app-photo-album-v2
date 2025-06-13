<?php
session_start();
require __DIR__ . "/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
require 'includes/database.php';
require ("includes/helper.php");

$customCssFiles = [];

function registerCss($cssFile) {
    global $customCssFiles;
    if (!in_array($cssFile, $customCssFiles)) {
        $customCssFiles[] = $cssFile;
    }
}

if (isset($_GET['disconnect'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    $component = $_GET['component'] ?? null;
    if (file_exists("controller/$component.php")) {
        require "controller/$component.php";
    } else {
        throw new Exception("Component '$component' does not exist");
    }
    exit();
}

// for regular page loads, determine the component and load it BEFORE rendering HTML
$componentName = !empty($_GET['component'])
    ? htmlspecialchars($_GET['component'], ENT_QUOTES, 'UTF-8')
    : (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' ? 'admin' : 'landing');

ob_start();

// logic to determine which controller to load
$loadController = true;
switch ($componentName) {
    case 'login':
        if (isset($_SESSION['auth'])) {
            echo '<div class="alert alert-danger">Access Denied: You are already logged in.</div>';
            $componentName = 'landing';
        }
        break;
    case 'users': case 'user': case 'admin':
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo '<div class="alert alert-danger">Access Denied: Administrator privileges required.</div>';
        $componentName = 'landing';
    }
    break;
    case 'albums': case 'album' : case 'photos': case 'photo':
        if (!isset($_SESSION['auth'])) {
            echo '<div class="alert alert-danger">Access Denied: You must be logged in to access your photos.</div>';
            $loadController = false;
        }
        break;
}

// Load the controller
if ($loadController && file_exists("controller/$componentName.php")) {
    require "controller/$componentName.php";
} else if (!$loadController) {
    // Do nothing, controller shouldn't be loaded
} else {
    throw new Exception("Component '$componentName' does not exist");
}

$content = ob_get_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photo Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <?php foreach ($customCssFiles as $cssFile): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($cssFile); ?>">
    <?php endforeach; ?>
</head>
<body>
<div class="container-fluid"> <!--had to put container-fluid cuz bootstrap cooks my css alive-->
    <?php
    require "_partials/navbar.php";
    echo $content;
    ?>
</div>
<?php require "_partials/_toast.html"; ?>
<?php require "_partials/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>