<?php
$workers = [];
$worker_num = 1;
include "../lib/dynamic.php";
//echo PHP_EOL . "\n";exit;
function onReceive ($pipe) {
    global $workers;
    $worker = $workers[$pipe];
    $data = $worker->read();
    echo "RECV: " . $data;
    $file = new Dynamic();
    $file->inputLog();
}

//循环创建进程
for($i = 0; $i < $worker_num; $i++) {
    $process = new swoole_process(function(swoole_process $process){
        $i = 1;
        while($i++){
            $process->write("Worker#{$process->id}: hello master i: {$i}\n");
            //if($i > 5 and $process->id == 1) $process->exit();
            sleep(rand(1,8));
        }
    });
    $process->id = $i;
    $pid = $process->start();
    $workers[$process->pipe] = $process;
}

swoole_process::signal(SIGCHLD, function(){
    //表示字进程关闭，回收它
    $status = swoole_process::wait();
    echo "Worker#{$status['pid']} exit\n";
});

//将子进程的管道加入eventloop
foreach($workers as $process) {
    swoole_event_add($process->pipe, 'onReceive');
}
