<?php
/**
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

require_once $_SERVER["DOCUMENT_ROOT"]."/source/ui/forms.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/source/classes/User.class.php";

session_start();

if (!isset($_SESSION["user"])) {
    session_destroy();
    header("Location: ../../index.php");
    exit();
}

/** @var User $user */
$user = $_SESSION["user"];

if ($user->getPwd()->temp) {
    header("Location: ../changepw.php");
    exit();
}

if ($user->getRole() !== "admin") {
    header("Location: ../characters.php");
    exit();
}

$field = "";
$feedback = "";
$vi = false;

if (!empty($_POST)) {
    if (isset($_POST["name"]) && isset($_POST["new"]) && isset($_POST["conf"])) {
        $new = $_POST["new"];
        $conf = $_POST["conf"];
        $name = trim($_POST["name"]);
        try {
            $vi = validPasswordInput($new, $conf);
        } catch (Exception $e) {
            $field = "pw";
            $feedback = $e->getMessage();
        }
        if ($vi) {
            if (strlen($name) < 3) {
                $field = "name";
                $feedback = "User name has less than 3 characters.";
            } else {
                try {
                    $user::create($name, $new);
                    header("Location: ../characters.php");
                    exit();
                } catch (Exception $e) {
                    $field = "name";
                    $feedback = $e->getMessage();
                }
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
    <link rel="icon" href="../../dist/img/favicon.ico">

    <title>Pen & Paper - New User</title>

    <!-- Bootstrap core CSS -->
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../dist/css/pnp.min.css" rel="stylesheet">
</head>

<body class="main">

<?php include $_SERVER["DOCUMENT_ROOT"]."/pages/includes/nav.php"; ?>

<main role="main" class="container">

    <div class="starter-template">
        <h1>Create new user</h1>
        <form class="form-signin" method="post">
            <div class="form-label-group">
                <input type="text" name="name" id="inputName" class="form-control<?php if ($field === "name") {echo " is-invalid";} ?>" placeholder="User name" aria-describedby="passwordHelpBlock" required autofocus>
                <label for="inputName">User name</label>
                <small id="passwordHelpBlock" class="form-text text-muted">
                    User name must be at least 3 characters long.
                </small>
                <div class="invalid-feedback">
                    <?php echo $feedback; ?>
                </div>
            </div>

            <hr class="mb-4">

            <div class="form-label-group">
                <input type="password" name="new" id="inputNewPassword" class="form-control<?php if ($field === "pw") {echo " is-invalid";} ?>" placeholder="Enter new password" aria-describedby="passwordHelpBlock" required>
                <label for="inputNewPassword">Enter temporary password</label>
                <small id="passwordHelpBlock" class="form-text text-muted">
                    Password must be at least 6 characters long.
                </small>
                <div class="invalid-feedback">
                    <?php echo $feedback; ?>
                </div>
            </div>

            <div class="form-label-group">
                <input type="password" name="conf" id="inputConfPassword" class="form-control<?php if ($field === "pw") {echo " is-invalid";} ?>" placeholder="Confirm new password" aria-describedby="passwordHelpBlock" required>
                <label for="inputConfPassword">Confirm temporary password</label>
                <small id="passwordHelpBlock" class="form-text text-muted">
                    Password confirmation must match temporary password.
                </small>
                <div class="invalid-feedback">
                    <?php echo $feedback; ?>
                </div>
            </div>

            <button class="btn btn-lg btn-primary btn-block" id="sign-in" type="submit">Create</button>
        </form>
    </div>

</main>

<!-- jQuery -->
<script src="../../vendor/jquery/jquery-3.3.1.min.js"></script>

<!-- Bootstrap Bundle JavaScript -->
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
