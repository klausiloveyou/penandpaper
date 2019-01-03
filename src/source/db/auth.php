<?php
/**
 * Handle User Login and Creation
 * Author: felix.reinhardt
 */
require_once($_SERVER["DOCUMENT_ROOT"]."/config/mongodb.php");

/**
 * @param string $name The name of the user to be logged in
 * @param string $pw The password of the user to be logged in
 * @return string Returning an error message in case
 */
function loginUser($name, $pw) {
    global $manager;

    try {
        $query = new MongoDB\Driver\Query([ "name" => $name ]);
        $cursor = $manager->executeQuery("pnp.user", $query);
        $result = $cursor->toArray();
        $password = $result[0]->pwd;
    } catch (\MongoDB\Driver\Exception\Exception $e) {
        return "Can't log you in: ".$e->getMessage();
    }

    if (is_null($password)) {
        return "Username doesn't exist!";
    }

    if (!password_verify($pw, $password)) {
        return "Invalid password!";
    }

    /*
     * TODO: Login logic here
     */


}