<?php
$serv = new swoole_websocket_server("0.0.0.0", 9501);

$serv->set(array(
    'worker_num' => 4,
    'daemonize'  => true,
));
$serv->on('Open', function($server, $req) {
    
    $url = './chats.txt';
    $name = $req->get['name']; //当前链接人
    $ip = $req->server['remote_addr'];//当前连接人ip
    $fd = $req->fd;
    $data = json_encode(array('fd'=>$fd,'name'=>$name, 'ip' => $ip)).PHP_EOL;
    file_put_contents($url, $data, FILE_APPEND);
    
    $users = file_get_contents($url);
    $users = explode(PHP_EOL, $users);
    $unames = [];
    foreach($users as $u){
        if(!$u) continue;
        $u = json_decode($u, true);
        $unames[] = $u;
    }
    $data = [];
    $data['type'] = 'list';
    $data['data'] = $unames;
    var_dump($data);    
    echo "connection open: ".$fd."\n"; 
    foreach($server->connections as $fd){
        $server->push($fd, json_encode(array('type' =>'msg', 'data' => $name . '上线了')));
        $server->push($fd, json_encode($data));
    }
    $server->name = $name;
});

$serv->on('Message', function($server, $frame) {
    $str = $server->name . ' : ';
    foreach($server->connections as $fd) {
        $server->push($fd, json_encode(array('type' => 'msg', 'data' => $str . $frame->data)));
    }
    echo "message: ".$frame->data."\n";
});

$serv->on('Close', function($server, $rfd) {
    $url = './chats.txt';
    $name = $server->name;
    foreach($server->connections as $fd){
        if($rfd == $fd) continue;
        $server->push($fd, json_encode(array('type' => 'msg', 'data' => $name . '下线了')));
        $server->push($fd, json_encode(array('type' => 'del', 'data' => $rfd)));
    }
    $users = file_get_contents($url);
    $users = explode(PHP_EOL, $users);
    $str = '';
    foreach($users as $u){
        if(!$u) continue;
        $u = json_decode($u, true);
        if(isset($u['fd']) && $u['fd'] == $rfd ) continue;
        $str .= json_encode($u).PHP_EOL;
    }
    file_put_contents($url, $str);
    echo "connection close: ".$rfd."\n";
});

$serv->start();
