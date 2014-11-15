<?php

require "vendor/autoload.php";
require "src/Traits/JsonTrait.php";
require "src/Interfaces/RestInterface.php";
require "src/Interfaces/DatabaseInterface.php";
require "src/Abstracts/DatabaseAbstract.php";
require "src/Abstracts/RestAbstract.php";
require "src/Database.php";
require "src/PMA.php";

$database   = new \src\Database(__DIR__ . DIRECTORY_SEPARATOR . 'database.ini');
$database->connect();
$database->mapDatabase();
$database->max_queries = 10;

$pma        = new \src\PMA($database);
$pma->getUrlSegments();
$pma->getUrlParams();
$pma->hydrateDatabaseProperties();
$pma->authentifyRequest();
$pma->rest();