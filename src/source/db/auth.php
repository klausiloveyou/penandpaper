<?php
/**
 * Handle User Login/Logout and Creation
 * Author: klausiloveyou
 */
require_once $_SERVER["DOCUMENT_ROOT"]."/config/mongodb.php";

/**
 * @param string $name The name of the user to be logged in
 * @param string $pw The password of the user to be logged in
 * @return array Returning an error message in case
 */
function loginUser($name, $pw) {
    global $manager;

    try {
        $query = new MongoDB\Driver\Query([ "name" => $name ]);
        $cursor = $manager -> executeQuery("pnp.user", $query);
        $result = $cursor -> toArray();
        $password = $result[0] -> pwd -> hash;
        $id = (string) $result[0] -> _id;
    } catch (\MongoDB\Driver\Exception\Exception $e) {
        return [false, "Can't log you in: ".$e -> getMessage()];
    }

    if (is_null($password)) {
        return [false, "user"];
    }

    if (!password_verify($pw, $password)) {
        return [false, "pw"];
    }

    return [true, $id];
}

function changePassword($uid, $old, $new) {
    global $manager;

    if (isCorrectPassword($uid, $old)) {
        $new_data = array(
            '$set' => array(
                'pwd.hash' => password_hash($new, PASSWORD_DEFAULT),
                'pwd.temp' => false
            )
        );
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk -> update([ '_id' => new MongoDB\BSON\ObjectId($uid) ], $new_data);
        try {
            $result = $manager -> executeBulkWrite('pnp.user', $bulk);
        } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
            return [false, $e -> getMessage()];
        }
        return [true, $result -> getWriteErrors()];
    } else {
        return [false, "Current password was invalid."];
    }
}

/**
 * @param string $uid The id of the user
 * @param string $pw password to check against
 * @return bool|null Is the current password correct or not
 */
function isCorrectPassword($uid, $pw) {
    global $manager;

    try {
        $query = new MongoDB\Driver\Query([ "_id" => new MongoDB\BSON\ObjectId($uid) ]);
        $cursor = $manager -> executeQuery("pnp.user", $query);
        $result = $cursor -> toArray();
        $hash = $result[0] -> pwd -> hash;
    } catch (\MongoDB\Driver\Exception\Exception $e) {
        return null;
    }

    return password_verify($pw, $hash);
}