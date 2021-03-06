<?php
/**
 * Util methods for DB operations
 *
 * @author  Felix Reinhardt <klausiloveyou@gmail.com>
 */
require_once $_SERVER["DOCUMENT_ROOT"]."/config/mongodb.php";

/**
 * @param string $namespace
 * @param array $query
 * @param array $options
 * @return stdClass[]|null
 * @throws Exception
 */
function queryDocument($namespace, $query, $options = [ "limit" => 1 ])
{
    global $manager;
    if (is_null($manager)) {
        throw new Exception("Something is wrong with the DB connection, check logs.");
    }
    try {
        $q = new MongoDB\Driver\Query($query, $options);
        $c = $manager->executeQuery($namespace, $q);
        $r = $c->toArray();
    } catch (\MongoDB\Driver\Exception\Exception $e) {
        throw new Exception($e->getMessage());
    }
    if (count($r) > 0) {
        foreach ($r as $o) {
            $oa = (array) $o;
            if (!empty($oa)) {
                return $r;
            }
        }
    }
    return null;
}

/**
 * @param string $namespace
 * @param MongoDB\Driver\BulkWrite $bulk
 * @return MongoDB\Driver\WriteResult
 * @throws Exception
 */
function bulkWriteOperation($namespace, $bulk)
{
    global $manager;
    if (is_null($manager)) {
        throw new Exception("Something is wrong with the DB connection, check logs.");
    }
    try {
        return $manager->executeBulkWrite($namespace, $bulk);
    } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
        throw new Exception($e->getMessage());
    }
}

/**
 * @param string $namespace
 * @param MongoDB\BSON\ObjectId $id
 * @param array|object $update
 * @return MongoDB\Driver\WriteResult
 * @throws Exception
 */
function bulkUpdateOneByID($namespace, $id, $update)
{
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->update(['_id' => $id], $update);
    try {
        return bulkWriteOperation($namespace, $bulk);
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}

/**
 * @param string $namespace
 * @param array|object $document
 * @return MongoDB\Driver\WriteResult
 * @throws Exception
 */
function bulkInsertOne($namespace, $document)
{
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->insert($document);
    try {
        return bulkWriteOperation($namespace, $bulk);
    } catch (Exception $e) {
        throw new Exception($e->getMessage());
    }
}