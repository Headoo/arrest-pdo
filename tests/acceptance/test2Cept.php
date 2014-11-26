<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('check that get api works');
$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
$I->sendGET('test/1');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('{"id":"1","0":"1","username":"cacao_sucre","1":"cacao_sucre","email":"cacao_sucre@hotmail.fr","2":"cacao_sucre@hotmail.fr"}');
