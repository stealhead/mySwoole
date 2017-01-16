<?php
$serv = new swoole_server("0.0.0.0", 9506);
$serv->addListener("0.0.0.0", 9507, SWOOLE_TCP);
$serv->set(array(
    'worker_num' => 1,   //工作进程数量
    'daemonize' => false, //是否作为守护进程
));
$serv->on('connect', function ($serv, $fd){
    //global $timer;
    //$timer[] = swoole_timer_tick(3000, function(){
    //    echo "timer\n";
    //});
    echo "Client:Connect.\n";
});
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    $serv->send($fd, 'Swoole: '.$data);
    //if($data == 10){
    //    $serv->close($fd);
   //}
   echo "jieshou\n";
   echo "{$data}\n";
});
$serv->on('close', function ($serv, $fd) {
    //global $timer;
    //foreach($timer as  $tid) swoole_timer_clear($tid);
    echo "Client: Close.\n";
});
$serv->start();
