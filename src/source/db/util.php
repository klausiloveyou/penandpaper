<?php
/**
 * Util methods for DB operations
 * Author: klausiloveyou
 */
require_once $_SERVER["DOCUMENT_ROOT"]."/config/mongodb.php";

/**
 * @param string $id The object ID of the user document
 * @return string|null The name of the user
 */
function getUserNameByID($id) {
    global $manager;

    try {
        $query = new MongoDB\Driver\Query([ "_id" => new MongoDB\BSON\ObjectId($id) ]);
        $cursor = $manager -> executeQuery("pnp.user", $query);
        $result = $cursor -> toArray();
        $name = $result[0] -> name;
    } catch (\MongoDB\Driver\Exception\Exception $e) {
        return null;
    }

    return $name;
}

/**
 * @param string $id The object ID of the user document
 * @return bool|null Is admin or not
 */
function isAdmin($id) {
    global $manager;

    try {
        $query = new MongoDB\Driver\Query([ "_id" => new MongoDB\BSON\ObjectId($id) ]);
        $cursor = $manager -> executeQuery("pnp.user", $query);
        $result = $cursor -> toArray();
        $admin = ($result[0] -> role == "admin") ? true : false;
    } catch (\MongoDB\Driver\Exception\Exception $e) {
        return null;
    }

    return $admin;
}

/**
 * @param string $id The object ID of the user document
 * @return bool|null Has a temporary password or self set
 */
function hasTempPassword($id) {
    global $manager;

    try {
        $query = new MongoDB\Driver\Query([ "_id" => new MongoDB\BSON\ObjectId($id) ]);
        $cursor = $manager -> executeQuery("pnp.user", $query);
        $result = $cursor -> toArray();
        $temp = (bool) $result[0] -> pwd -> temp;
    } catch (\MongoDB\Driver\Exception\Exception $e) {
        return null;
    }

    return $temp;
}