<?php
class SwooleMy {
    private $_server;
    private $_tables;
    private $_coroutine;

    public function __construct () {
        if(!$this->_server instanceof swoole_server){
            $this->_server = new swoole_server("0.0.0.0", 9510);
            $this->_server->set(array(
                'worker_num' => 8,
                'daemonize' => 0
            ));
            $this->_server->on("Connect", array($this, 'connect'));
            $this->_server->on("WorkerStart", array($this, 'workerStart'));
            $this->_server->on("Receive", array($this, 'receive'));
            $this->_server->on("Task", array($this, 'task'));
            $this->_server->on("Close", array($this, 'close'));
            $this->_server->on("Start", array($this, 'start'));
        }
    }
    public function run () {
        $this->_server->start();
    }

    public function start ($server) {
        echo "swoole_server start\n";
    }

    public function connect ($server, $fd, $from_id) {                
        echo "starttime: {time()}\n";
        $client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
        $client->connect("127.0.0.1", 8888, 0.5);
        $client->send("hello world from swoole");
        $client2 = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
        $client2->connect("127.0.0.1", 8889, 0.5);
        $client2->send("hello world from swooleis");
        $ret = $client->recv();
        $ret2 = $client2->recv();
        $client->close();
        $client2->close();
        echo "endtime: {time()}\n";
        echo $ret . $ret2;
    }
    
    public function workerStart () {
    
    }

    public function task () {
    
    }

    public function receive ($server, $fd, $from_id, $data) {
    
        $client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
        $client->connect("127.0.0.1", 8888, 0.5);
        $client->send("hello world from swoole");
        $ret = $client->recv();
        $client->close();
        echo $ret;
    }

    public function close () {
    
    }


}

$server = new SwooleMy();
$server->run();
