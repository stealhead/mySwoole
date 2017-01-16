<?php
$count = 0;
$pool = new SplQueue();
$server = new Swoole\Http\Server('0.0.0.0', 9521, SWOOLE_BASE);

$server->on('Request', function($request, $response) use(&$count, $pool){
    if($request->server['request_uri'] == '/favicon.ico') {
        //ob_start();
        //echo file_get_contents("./index.html");
        //$content = ob_get_contents();
        //ob_end_clean();
        //$response->end($content);
        $response->end('i am favicon.ico');
        return;
    }
    $get = $request->get;
    if(count($pool) == 0){
        //$redis = new Swoole\Coroutine\Redis();
        $redis = new Redis();
        $res = $redis->connect('127.0.0.1', 6379);
        $redis->auth('wg123456');
        if($res == false){
            $response->end("redis connect fail!" . PHP_EOL);
            return;
        }
        $pool->push($redis);
        $count++;
    }
    $redis = $pool->pop();
    foreach($get as $k => $v){
        $ret = $redis->set('key', $k . '-' . $v);
    }
    $response->end("swoole response is ok, count = $count, result=" . var_export($ret . PHP_EOL, true));
    $pool->push($redis);
});
$server->on("Start", function (){
    echo "swoole server is startting...".PHP_EOL;
});
$server->start();
