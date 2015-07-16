<?php

require "../vendor/autoload.php";
require "../src/Traits/JsonTrait.php";
require "../src/Interfaces/RestInterface.php";
require "../src/Interfaces/DatabaseInterface.php";
require "../src/Interfaces/DatabaseDriverInterface.php";
require "../src/Abstracts/DatabaseDriverAbstract.php";
require "../src/Abstracts/DatabaseAbstract.php";
require "../src/Abstracts/RestAbstract.php";
require "../src/Database.php";
require "../src/PMA.php";

$directory = __DIR__.DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "app" . 
    DIRECTORY_SEPARATOR."prod".DIRECTORY_SEPARATOR."conf".DIRECTORY_SEPARATOR;

$database   = new \src\Database($directory.'database.ini');
$database->connect();
$database->setCustomPkFieldsPerTable(array('mail_user' => 'mailuser_id'));
$database->mapDatabase();
$database->max_queries = 10;

$pma        = new \src\PMA($database, $directory.'ips.ini');
$pma->getUrlSegments();
$pma->getUrlParams();
$pma->allowedTables(array('mail_user'));
$pma->forbiddenMethods(array('GET', 'PUT', 'DELETE'));
$pma->hydrateDatabaseProperties();
$pma->authentifyRequest();
$pma->rest();
