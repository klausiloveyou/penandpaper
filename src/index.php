<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/source/db/auth.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/source/db/util.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/config/mongodb.php";

session_start();
$error = [];

if (isset($_SESSION["user_id"])) {
    header("Location: pages/characters.php");
    exit();
}

if (!empty($_POST)) {
    if (isset($_POST["pw"]) && isset($_POST["user"])) {

        $p = $_POST["pw"];
        $u = trim($_POST["user"]);

        if (strlen($p) == 0 || strlen($u) == 0) {
            $error = [false, "user"];
        } else {
            $login = loginUser($u, $p);
            if ($login[0]) {
                $_SESSION["user_id"] = $login[1];
                $_SESSION["temp_pw"] = hasTempPassword($login[1]);
                header("Location: pages/characters.php");
                exit();
            } else {
                $error = $login;
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Pen & Paper">
    <meta name="author" content="klausiloveyou">
    <link rel="icon" href="dist/img/favicon.ico">

    <title>Pen & Paper - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dist/css/pnp.css" rel="stylesheet">
</head>

<body class="login">

<form class="form-signin" method="post">
    <div class="form-label-group">
        <input type="text" name="user" id="inputName" class="form-control<?php if (!$error[0] && $error[1] === "user") {echo " is-invalid";} ?>" placeholder="User name" required autofocus>
        <label for="inputName">User Name</label>
        <div class="invalid-feedback">
            User name doesn't exist!
        </div>
    </div>

    <div class="form-label-group">
        <input type="password" name="pw" id="inputPassword" class="form-control<?php if (!$error[0] && $error[1] === "pw") {echo " is-invalid";} ?>" placeholder="Password" required>
        <label for="inputPassword">Password</label>
        <div class="invalid-feedback">
            Wrong password!
        </div>
    </div>

    <button class="btn btn-lg btn-primary btn-block" id="sign-in" type="submit">Sign in</button>
</form>

<!-- jQuery -->
<script src="vendor/jquery/jquery-3.3.1.min.js"></script>

<!-- Bootstrap Bundle JavaScript -->
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>