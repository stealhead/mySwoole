<?php
$serv = new swoole_websocket_server("0.0.0.0", 9502);

$serv->on('Open', function($server, $req) {
    $fd = $req->fd;
    echo "connection open: ".$fd."\n"; 
});

$serv->on('Message', function($server, $frame) {
    foreach($server->connections as $fd) {
        $server->push($fd, $frame->data);
    }
    echo "message: ".$frame->data."\n";
});

$serv->on('Close', function($server, $fd) {
    echo "connection close: ".$fd."\n";
});

$serv->start();
