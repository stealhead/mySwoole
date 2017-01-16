<?php
$http = new swoole_http_server('0.0.0.0', 9511, SWOOLE_BASE, SWOOLE_SOCK_TCP);

$http->set(array(
            'worker_num' => 2,
        )
    );
$http->on('request', function($request, $response) {
    echo "connect...\n";
    var_dump($request);
    $response->end("hello");    
});

$http->start();
