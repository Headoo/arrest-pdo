<?php

$linkPost = 'http://localhost/index.php/test';
$postfieldsPost = array(
    'username' => 'cacao_sucre',
    'email' => 'cacao_sucre@hotmail.fr',
);
$curl = curl_init();
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_URL, $linkPost);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper('post'));
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postfieldsPost));
$result = curl_exec($curl);
curl_close($curl);

echo var_dump($result);