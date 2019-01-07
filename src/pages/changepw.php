<?php
/**
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

require_once $_SERVER["DOCUMENT_ROOT"]."/source/ui/forms.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/source/classes/User.class.php";

session_start();

if (!isset($_SESSION["user"])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

/** @var User $user */
$user = $_SESSION["user"];

$field = "";
$feedback = "";
$vi = false;
$temp = $user->getPwd()->temp;

if (!empty($_POST)) {
    if (isset($_POST["old"]) && isset($_POST["new"]) && isset($_POST["conf"])) {
        try {
            $vi = validPasswordInput($_POST["new"], $_POST["conf"]);
        } catch (Exception $e) {
            $field = "new";
            $feedback = $e->getMessage();
        }
        if ($vi) {
            try {
                $user->changePassword($_POST["old"], $_POST["new"]);
                session_destroy();
                header("Location: ../index.php");
                exit();
            } catch (Exception $e) {
                $field = "old";
                $feedback = $e->getMessage();
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
    <link rel="icon" href="../dist/img/favicon.ico">

    <title>Pen & Paper - Change Password</title>

    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../dist/css/pnp.css" rel="stylesheet">
</head>

<body class="main">

<?php if (!$temp) { include $_SERVER["DOCUMENT_ROOT"]."/pages/includes/nav.php"; } ?>

<main role="main" class="container">

    <div class="starter-template">
        <h1>Change your <?php if ($temp) { echo "temporary "; } ?>password</h1>
        <form class="form-signin" method="post">
            <div class="form-label-group">
                <input type="password" name="old" id="inputOld" class="form-control<?php if ($field === "old") {echo " is-invalid";} ?>" placeholder="Enter current password" required autofocus>
                <label for="inputOld">Enter current password</label>
                <div class="invalid-feedback">
                    <?php echo $feedback; ?>
                </div>
            </div>

            <hr class="mb-4">

            <div class="form-label-group">
                <input type="password" name="new" id="inputNewPassword" class="form-control<?php if ($field === "new") {echo " is-invalid";} ?>" placeholder="Enter new password" required>
                <label for="inputNewPassword">Enter new password</label>
                <div class="invalid-feedback">
                    <?php echo $feedback; ?>
                </div>
            </div>

            <div class="form-label-group">
                <input type="password" name="conf" id="inputConfPassword" class="form-control<?php if ($field === "new") {echo " is-invalid";} ?>" placeholder="Confirm new password" required>
                <label for="inputConfPassword">Confirm new password</label>
                <div class="invalid-feedback">
                    <?php echo $feedback; ?>
                </div>
            </div>

            <button class="btn btn-lg btn-primary btn-block" id="sign-in" type="submit">Change</button>
        </form>
    </div>

</main>

<!-- jQuery -->
<script src="../vendor/jquery/jquery-3.3.1.min.js"></script>

<!-- Bootstrap Bundle JavaScript -->
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>