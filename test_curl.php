<?php
$url = 'http://localhost/simonel/api/send?api_key=WRONG123';
$response = file_get_contents($url);
echo "GET response:\n";
var_dump($response);

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode(['api_key' => 'WRONG123', 'voltage' => 220])
    ]
];
$context  = stream_context_create($options);
$response = file_get_contents('http://localhost/simonel/api/send', false, $context);
echo "POST response:\n";
var_dump($response);
