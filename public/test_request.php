<?php

// TEST REQUEST
$private_key = 'private_key_999';
$public_key = 'public_key_999';

$data = "";

$signature = urlencode(base64_encode(hash_hmac("sha256", $data, $private_key, TRUE)));

//$url = 'http://localhost:8888/person.php?id=40';
$url = 'http://localhost:8888/people.php';
$signed_request = array('data'=>$data, 'signature'=>$signature, 'public_key'=>$public_key);

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($signed_request),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo $result;

?>