<?php
$cli = new Swoole\Http\Client("127.0.0.1", "9510");
$cli->post("/post.php", array("a" => 123, 'b' => 456), function($cli){
    echo "length: " . strlen($cli->body) . "\n";
    echo $cli->body;
});

