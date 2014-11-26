<?php
$I = new AcceptanceTester($scenario);
$fields = array(
    'username' => 'florilege_edit',
    'email' => 'florilege_edit@hotmail.fr',
);
$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
$I->sendDELETE('test/1', $fields);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->dontSeeInDatabase('test', $fields);
$I->seeResponseContains('{"status":"success","content":{"message":"Request successfully done!","code":200}}');
