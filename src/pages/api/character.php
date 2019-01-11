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
$lastUsed = $user->getLastUsed();

if ($user->getPwd()->temp) {
    header("Location: ../changepw.php");
    exit();
}

if (!empty($_POST) && !empty($_GET)) {
    header("HTTP/1.1 400 Bad Request");
    exit();
}

if (!empty($_POST)) {
    if (isset($_POST["chardata"]) && isset($_POST["operation"])) {
        $operation = $_POST["operation"];
        $chardata = json_decode($_POST["chardata"]);
        if ($operation === "create") {
            try {
                $ncid = Character::create($user->getId(), $chardata);
                $user->setLastUsed($ncid);
            } catch (Exception $e) {
                header("HTTP/1.1 400 Bad Request");
                echo $e->getMessage();
                exit();
            }
        } else if ($operation === "update") {

        } else {
            header("HTTP/1.1 400 Bad Request");
            echo "The operation you provided is not valid.";
            exit();
        }
    }
}

if (!empty($_GET)) {
    if (isset($_GET["what"])) {
        try {
            header('Content-Type: application/json');
            echo json_encode($lastUsed->fetchJSONData($_GET["what"]));
        } catch (Exception $e) {
            header("HTTP/1.1 400 Bad Request");
            echo $e->getMessage();
            exit();
        }
    }
}