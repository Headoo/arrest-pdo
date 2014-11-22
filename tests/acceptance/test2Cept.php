<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('check that get api works');
$I->amOnPage('test/1');
$result = '{"id":"1","0":"1","username":"cacao_sucre","1":"cacao_sucre","email":"cacao_sucre@hotmail.fr","2":"cacao_sucre@hotmail.fr"}';
$I->see($result);
