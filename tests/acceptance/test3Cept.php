<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('check that put api works');
$linkPut = 'http://localhost:8888/test/1';
$postfieldsPut = array(
    'username' => 'florilege_edit',
    'email' => 'florilege_edit@hotmail.fr'
);
$curl = curl_init();
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_URL, $linkPut);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper('put'));
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postfieldsPut));  
curl_exec($curl);
curl_close($curl);
$I->seeInDatabase('test', $postfieldsPut);
