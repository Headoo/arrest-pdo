<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('check that put api works');
$fields = array(
    'username' => 'florilege_edit',
    'email' => 'florilege_edit@hotmail.fr',
);
$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
$I->sendPUT('test/1', $fields);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeInDatabase('test', $fields);
$I->seeResponseContains('{"status":"success","content":{"message":"Request successfully done!","code":200}}');
