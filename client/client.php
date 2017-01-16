<?php
$client = new swoole_client(SWOOLE_SOCK_TCP);
//设置事件回调函数
$client->on("connect", function($cli) {
    //for($i = 1; $i <=10; $i++){
    	//$cli->send($i);
    //}
    $cli->send(1);
});
$client->on("receive", function($cli, $data){
    echo "Received: ".$data."\n";
});
$client->on("error", function($cli){
    echo "Connect failed\n";
});
$client->on("close", function($cli){
    echo "Connection close\n";
});
//发起网络连接
$client->connect('114.215.154.164', 9511, 0.5);
$client->send('hh');
