<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('check that post api works');
$linkPost = 'http://localhost/test';
$postfieldsPost = array(
    'username' => 'cacao_sucre',
    'email' => 'cacao_sucre@hotmail.fr'
);
$curl = curl_init();
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_URL, $linkPost);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper('post'));
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postfieldsPost));  
curl_exec($curl);
curl_close($curl);
$I->seeInDatabase('test', $postfieldsPost);
