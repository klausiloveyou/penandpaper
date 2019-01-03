<?php
define("DB_SERVER", "pnpdevdb");
define("DB_USERNAME", "pnpdevuser");
define("DB_PASSWORD", "pnpdevsec");

try {
    $manager = new MongoDB\Driver\Manager("mongodb://".DB_USERNAME.":".DB_PASSWORD."@".DB_SERVER."/pnp");
} catch (\MongoDB\Driver\Exception\Exception $exception) {
    echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?</p><br>'.$exception;
}
