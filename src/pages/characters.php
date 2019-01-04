<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/source/db/util.php";

session_start();

if (!isset($_SESSION["user_id"])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
} else if ($_SESSION["temp_pw"]) {
    header("Location: changepw.php");
    exit();
} else {
    //TODO: Insert logic here
}

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

<?php include "includes/nav.php"; ?>

<main role="main" class="container">

    <div class="starter-template">
        <h1>Bootstrap starter template</h1>
        <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
    </div>

</main>

<!-- jQuery -->
<script src="../vendor/jquery/jquery-3.3.1.min.js"></script>

<!-- Bootstrap Bundle JavaScript -->
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>