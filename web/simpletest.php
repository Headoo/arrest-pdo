<?php

require "../vendor/autoload.php";
require "../src/Traits/JsonTrait.php";
require "../src/Interfaces/RestInterface.php";
require "../src/Interfaces/DatabaseInterface.php";
require "../src/Interfaces/DatabaseDriverInterface.php";
require "../src/Abstracts/DatabaseAbstract.php";
require "../src/Abstracts/DatabaseDriverAbstract.php";
require "../src/Abstracts/RestAbstract.php";
require "../src/Database.php";
require "../src/PMA.php";

$directory = __DIR__.DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "app" . 
    DIRECTORY_SEPARATOR."test".DIRECTORY_SEPARATOR."conf".DIRECTORY_SEPARATOR;

$database   = new \src\Database($directory.'database.ini');
$database->connect();
$database->setCustomPkFieldsPerTable(array());
$database->mapDatabase();
$database->max_queries = 10;

$pma        = new \src\PMA($database, $directory.'ips.ini');
$pma->getUrlSegments('test.php');
$pma->getUrlParams();
$pma->allowedTables(array('test'));
$pma->forbiddenMethods(array());
$pma->hydrateDatabaseProperties();
$pma->authentifyRequest();
$pma->rest();
