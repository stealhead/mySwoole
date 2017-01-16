<?php

$http = new swoole_http_server('0.0.0.0', 9510);

$http->set(array(
    'worker_num' => 1,
    'daemonize'  => true
));

$http->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    //print_r($request);
    $pathinfo = $request->server['path_info'];
    $filename = __DIR__ . $pathinfo;
    if(is_file($filename)) {
        $ext = pathinfo($pathinfo, PATHINFO_EXTENSION);

        if('php' == $ext) {
            ob_start();
            #include $filename;
            include "b.php";
            $content = ob_get_contents();
            ob_end_clean();
            $response->end($content);
        } else {
            $content = file_get_contents($filename);
            $response->end($content);
        }
    } else {
        $response->status(404);
        $response->end('404 not foundis');
    }
});

//开启
$http->start();
