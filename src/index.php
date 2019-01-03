<?php
require_once "source/db/auth.php";
require_once "source/ui/forms.php";
require_once "config/mongodb.php";

$error = "";

if (isset($_POST["pw"]) && isset($_POST["user"])) {

    $p = $_POST["pw"];
    $u = trim($_POST["user"]);

    if (strlen($p) == 0 || strlen($u) == 0) {
        $error = "Provide a user name and password!";
    } else {
        $error = loginUser($u, $p);
    }
} else {
    #do_on_load_stuff
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Pen & Paper">
    <meta name="author" content="da_fuchs">
    <link rel="icon" href="dist/img/favicon.ico">

    <title>Pen & Paper - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dist/css/pnp.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <!-- <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"> -->
</head>

<body class="text-center" background="dist/img/paper.jpg">
<form class="form-signin" method="post">
    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
    <!-- DEFAULT DEV USER: admin -->
    <label for="inputEmail" class="sr-only">User name</label>
    <input name="user" type="text" id="inputEmail" class="form-control" placeholder="User name" required autofocus>
    <!-- DEFAULT DEV PW: secret -->
    <label for="inputPassword" class="sr-only">Password</label>
    <input name="pw" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>

<!-- jQuery -->
<script src="vendor/jquery/jquery-3.3.1.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Bootstrap Bundle JavaScript -->
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>