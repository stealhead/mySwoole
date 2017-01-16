<?php
$http = new swoole_http_server("0.0.0.0", 9510);
$http->on("request", function($request, $response){
    echo $request->server['path_info'] . "\n";
    $path_info = $request->server['path_info'];
    $path_info = trim($path_info, "/");
    if(file_exists($path_info)){
        require_once("./" . $path_info);
        run($request);
    }
    $response->end("<h1>Hello Swoole" . rand(100, 999) . "</h1>");
});
$http->on("Connect", function($server, $fd, $from_id){
    echo "connect from ". $from_id. "\n";
});
$http->start();
