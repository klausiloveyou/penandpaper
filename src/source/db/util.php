<?php
/**
 * Util methods for DB operations
 * Author: klausiloveyou
 */
require_once $_SERVER["DOCUMENT_ROOT"]."/config/mongodb.php";

/**
 * @param string $namespace
 * @param array $query
 * @param array $options
 * @return array|null
 * @throws Exception
 */
function queryDocument($namespace, $query, $options = [ "limit" => 1 ])
{
    global $manager;
    try {
        $q = new MongoDB\Driver\Query($query, $options);
        $c = $manager->executeQuery($namespace, $q);
        $r = $c->toArray();
    } catch (\MongoDB\Driver\Exception\Exception $e) {
        throw new Exception($e->getMessage());
    }
    return (count($r) > 0) ? $r : null;
}

/**
 * @param string $namespace
 * @param MongoDB\Driver\BulkWrite $bulk
 * @throws Exception
 */
function bulkWriteOperation($namespace, $bulk)
{
    global $manager;
    try {
        $manager->executeBulkWrite($namespace, $bulk);
    } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
        throw new Exception($e->getMessage());
    }
}