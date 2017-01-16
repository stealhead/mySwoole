<?php
class Server
{
    private $_server;
    private $_conf = array(
        'worker_num' => 4,
        'task_worker_num' => 10
    );

    public function __construct()
    {
        if(!$this->_server instanceof swoole_server){
            $this->_server = new Swoole\Server('0.0.0.0', 9511);
        
        $this->_server->set($this->_conf);
        $this->_server->on("Start", array($this, 'start'));
        $this->_server->on("Connect", array($this, 'connect'));
        $this->_server->on("Receive", array($this, 'receive'));
        $this->_server->on("Close", array($this, 'close'));
        $this->_server->on("Task", array($this, 'task'));
        $this->_server->on("Finish", array($this, 'finish'));
        }
    }

    public function run()
    {
        $this->_server->start();
    }

    public function start($serv)
    {
        echo "server is startting...\n";
    }

    public function connect($serv, $fd, $from_id)
    {
        echo "server is connectting...\n";
        echo "fd is {$fd}\n";
    }

    public function receive($serv, $fd, $from_id, $data)
    {
        $tcpclient = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
        $tcpclient->connect("127.0.0.1", 9506, 0.5);
        $tcpclient->send("i am coroutine");
        echo "receive some data:\n";
        if($data){
            $serv->task($tcpclient->recv());
            //$serv->tick(1000, function() use ($serv, $data, $tcpclient){
                //$data .= rand(1000,9999);
                $serv->task($data);
                //$serv->task($tcpclient->recv());
            //});
        }
        $serv->send($fd, "hello client");
    }

    public function close ($serv, $fd , $from_id)
    {
        echo "server is close from_id is {$from_id}\n";
    }

    public function task($serv, $task_id, $src_worker_id, $data )
    {
        $filename = "/home/www/swoole/server/thinkput.log";
        file_put_contents($filename, json_encode($data) . PHP_EOL, FILE_APPEND);
        $serv->finish("{$task_id} has finish");
    }

    public function finish($serv, $task_id, $data)
    {
        echo "{$data}\n";
    }
}

$serv = new Server();
$serv->run();
