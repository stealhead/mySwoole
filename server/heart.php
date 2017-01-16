<?php
$serv = new swoole_server("0.0.0.0", 9501);

$serv->set(
    array(
        'worker_num' => 4,
        'heartbeat_check_interval' => 5,
        'heartbeat_idle_time' => 10,
    )
);

$serv->on("connect", function($serv, $fd, $form_id){
    echo "connect success\n";
});

$serv->on("receive", function($serv, $fd, $form_id, $data){
    echo "receive $data\n";
});

$serv->on("close", function($serv, $fd, $form_id){
    echo "close success\n";
});

$serv->start();
