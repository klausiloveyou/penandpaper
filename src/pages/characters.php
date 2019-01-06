<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/source/classes/User.class.php";

session_start();

if (!isset($_SESSION["user"])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

/** @var User $user */
$user = $_SESSION["user"];

if ($user->getPwd()->temp) {
    header("Location: changepw.php");
    exit();
}

$lastUsed = $user->getLastUsed();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Pen & Paper">
    <meta name="author" content="klausiloveyou">
    <link rel="icon" href="../dist/img/favicon.ico">

    <title>Pen & Paper - Characters</title>

    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../dist/css/pnp.css" rel="stylesheet">
</head>

<body class="main">

<?php include $_SERVER["DOCUMENT_ROOT"]."/pages/includes/nav.php"; ?>

<main role="main" class="container">
    <?php include (is_null($lastUsed)) ? $_SERVER["DOCUMENT_ROOT"]."/pages/includes/nocharactermain.php" : $_SERVER["DOCUMENT_ROOT"]."/pages/includes/charactermain.php"; ?>
</main>

<!-- jQuery -->
<script src="../vendor/jquery/jquery-3.3.1.min.js"></script>

<!-- Bootstrap Bundle JavaScript -->
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>