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

if (!empty($_POST) && !empty($_GET)) {
    session_destroy();
    header("HTTP/1.1 400 Bad Request");
    exit();
}

if (!empty($_POST)) {
    if (isset($_POST["chardata"]) && isset($_POST["method"])) {

    }
}

if (!empty($_GET)) {
    if (isset($_GET["charid"]) && isset($_GET["what"])) {

    }
}

