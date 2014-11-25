<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('check that delete api works');
$linkDelete = 'http://localhost/index.php/test/1';
$postfieldsPut = array(
    'username' => 'florilege_edit',
    'email' => 'florilege_edit@hotmail.fr',
);
$curl = curl_init();
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_URL, $linkDelete);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper('delete'));
curl_exec($curl);
curl_close($curl);
$I->dontSeeInDatabase('test', $postfieldsPut);
