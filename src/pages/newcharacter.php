<?php
/**
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

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

$char = null;
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
    <form class="api">
        <?php include $_SERVER["DOCUMENT_ROOT"]."/pages/includes/character/profile.php"; ?>
        <div class="container btn-group fullwidth" role="group">
            <button class="btn btn-lg btn-primary mr-1 rounded-right" id="create">Create</button>
            <button class="btn btn-lg btn-primary ml-1 rounded-left" id="cancel">Cancel</button>
        </div>
    </form>
</main>

<!-- jQuery -->
<script src="../vendor/jquery/jquery-3.3.1.min.js"></script>

<!-- Bootstrap Bundle JavaScript -->
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- PNP JS -->
<script src="../dist/js/pnp.js" type="text/javascript"></script>

<!-- Init PNP JS -->
<script>
    if (window.pnp) {
        $(document).ready(function() {
            pnp.character.init();
        });
    }
</script>
</body>