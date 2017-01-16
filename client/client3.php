<?php
$client = new swoole_client(SWOOLE_SOCK_TCP);
if(!$client->connect('114.215.154.164', 9511, -1))
{
    exit("connect failed\n");
}
$client->send("hello server");
echo $client->recv();
$client->close();
