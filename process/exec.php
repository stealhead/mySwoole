<?php
$process = new swoole_process('callback_function', true);
$pid = $process->start();
function callback_function(swoole_process $worker)
{
    $arr = array('info.php', 123);
    $worker->exec('/usr/local/webserver/php/bin/php', $arr);
}
echo "From Worker: ".$process->read();
$process->write("hello worker\n");
echo "From Worker: ".$process->read();
$ret = swoole_process::wait();
var_dump($ret);
