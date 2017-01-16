<?php
$http = swoole_http_server('0.0.0.0', 9501);

$http->set(array(
            'worker_num' => 4,
            'demonize'   => true
        )
    );
$http->on('request', function($request, $response) {
    
});
