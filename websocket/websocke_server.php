<?php
$serv = new swoole_websocket_server("0.0.0.0", 9503);

$serv->on('Open', function($server, $req) {
    $fd = $req->fd;
    echo "connection open: ".$fd;
    //$his = explode(PHP_EOL, $history);
    $server->tick(1000, function() use ($server, $fd){
	    $random = mt_rand(100,1000);
	    $meminfo = shell_exec('cat /proc/meminfo | grep Active: | awk \'{print $2}\'');
        file_put_contents('./memory.log', $meminfo . PHP_EOL, FILE_APPEND);
	    //$meminfo = json_encode($meminfo);
	    $server->push($fd, $meminfo);
    });
    $history = file_get_contents('./memory.log'); 
    echo $history;
});

$serv->on('Message', function($server, $frame) {
    echo "message: ".$frame->data;
    $server->push($frame->fd, json_encode(["hello", "world"]));
});

$serv->on('Close', function($server, $fd) {
    echo "connection close: ".$fd;
});

$serv->start();
