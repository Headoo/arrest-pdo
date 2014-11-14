<?php

require "vendor/autoload.php";
require "src/Interfaces/RestInterface.php";
require "src/Interfaces/DatabaseInterface.php";
require "src/Abstracts/DatabaseAbstract.php";
require "src/Abstracts/RestAbstract.php";
require "src/Database.php";
require "src/PMA.php";

$database   = new \src\Database(__DIR__ . DIRECTORY_SEPARATOR . 'database.ini');
$database->connect();
$database->mapDatabase();

$pma        = new \src\PMA($database);
$pma->getUriSegments();
$pma->hydrate();
$pma->secureBeforeRequest();

$pma->rest();