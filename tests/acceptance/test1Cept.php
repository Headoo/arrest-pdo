<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('check that post api works');
$fields = array(
    'username' => 'cacao_sucre',
    'email' => 'cacao_sucre@hotmail.fr',
);
$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
$I->sendPOST('test', $fields);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeInDatabase('test', $fields);
$I->seeResponseContains('{"status":"success","content":{"message":"Request successfully done!","code":200}}');
