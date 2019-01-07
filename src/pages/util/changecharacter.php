<?php
/**
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */

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

if (!empty($_GET)) {
    if (isset($_GET["c"])) {
        try {
            $user->setLastUsed(new MongoDB\BSON\ObjectId($_GET["c"]));
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}

header("Location: ../characters.php");
exit();