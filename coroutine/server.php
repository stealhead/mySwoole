<?php
$serv = new swoole_server('127.0.0.1', 8888, SWOOLE_BASE, SWOOLE_SOCK_TCP);
$serv->set(array(
    'worker_num' => 4,
    'daemonize' =>0
));
$serv->on("Connect", "my_onConnect");
$serv->on("Receive", "my_onReceive");
$serv->on("Close", "my_onClose");
function my_onConnect($serv, $fd, $from_id){
    echo "$from_id has success connect\n";
}
function my_onReceive($serv, $fd, $from_id, $data) {
    echo "has receive $data \n";
}

function my_onClose($serv, $fd, $from_id) {
    echo "$from_id has close\n";
}
$serv->start();
