<?php
define('DB_SERVER', 'pnpdevdb');
define('DB_DB', 'pnp');
define('DB_USERNAME', 'pnpdevuser');
define('DB_PASSWORD', 'pnpdevsec');

try {
    $manager = new MongoDB\Driver\Manager("mongodb://".DB_USERNAME.":".DB_PASSWORD."@".DB_SERVER."/".DB_DB);
} catch (\MongoDB\Driver\Exception\Exception $e) {
    error_log($e->getMessage());
    $manager = null;
}