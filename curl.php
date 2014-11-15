<?php

$type = (!empty(filter_input(INPUT_GET, 'type'))) ? filter_input(INPUT_GET, 'type') : 'post';

if (($type === 'post')) {
    $link = 'http://localhost/headoo_user';
    $postfields = array(
        'username' => 'caco_sucre',
        'email' => 'caco_sucre@hotmail.fr',
        'password' => 'verinostat',
    );
    
} else if ($type === 'put') {
    $link = 'http://localhost/headoo_user/130';
    $postfields = array(
        'username' => 'tunis_power',
        'email' => 'tunis_power@hotmail.fr',
        'password' => 'caillassage',
    );  
} else if ($type === 'delete') {
    $link = 'http://localhost/headoo_user/131';
    $postfields = array();  
}


$curl = curl_init();
curl_setopt($curl, CURLOPT_COOKIESESSION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_URL, $link);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($type));
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postfields));  

$return = curl_exec($curl);
curl_close($curl);

echo $return;