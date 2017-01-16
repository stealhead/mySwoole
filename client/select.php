<?php

$clients = array();

for ($i=1; $i<=90; $i++)
{
	$client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);
	$ret = $client->connect('114.215.154.164', 9502, 0.5, 0);
	if(!$ret) echo "connect server fail errcode=" . $client->errCode;
	else
	{
		$client->send($i);
		$clients[$client->sock] = $client;
	}
}

while (!empty($clients))
{
    $write = $error = array();
    $read = array_values($clients);
    $n = swoole_client_select($read, $write, $error, 0.6);
    if ($n > 0)
    {
    $data = array();
	foreach ($read as $index => $c)
	{
	    echo "recv #{$c->sock}:" . $c->recv() . "\n";
        $data[] = $c->recv();
	    $c->close();
	    unset($clients[$c->sock]);
	}
    foreach($data as $da) {
    
    }
    
    }
}
