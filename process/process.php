<?php
$workers = [];
$worker_num = 2;

for($i = 0; $i < $worker_num; $i++) {
    $process = new swoole_process('callback_function', false, false);
    $process->useQueue();
    $pid = $process->start();
    $workers[$pid] = $process;
}

function callback_function (swoole_process $worker) {
    while(true) {
        $recv = $worker->pop();
        echo "From Master: $recv\n";
    }
    sleep(2);
    $worker->exit(0);
}

while(true) {
    $pid = array_rand($workers);
    $process = $workers[$pid];
    $process->push("hello worker\n");
    echo "i have push to you\n";
    sleep(1);
}
/*
for($i = 0; $i < $worker_num; $i++) {
    $ret = swoole_process::wait();
    $pid = $ret['pid'];
    unset($workers[$pid]);
    echo "Worker Exit, PID=" . $pid . PHP_EOL;
}
*/
