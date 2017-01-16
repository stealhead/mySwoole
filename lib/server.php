<?php
class sw_server{
    protected $_serv;
    protected $_conf = array(
                        'worker_num' => 4,
                        'daemonize' => false
        
                        );
    protected $_port = 9511;
    protected $_host = '0.0.0.0';
    
    public function __construct() {
        $this->_serv = new swoole_server($this->_host, $this->_port);
        $this->_serv->on("connect", array($this, 'connect'));
        $this->_serv->on("receive", array($this, 'receive'));
        $this->_serv->on("close", array($this, 'close'));
    }

    public function start(){
        $this->_serv->start();
    }

    public function connect(swoole_server $server, $fd, $form_id) {
        echo "connect\n"; 
    }

    public function receive(swoole_server $server, $fd, $form_id, $data) {
        var_dump($data);
        echo "receive\n";
    }

    public function close(swoole_server $server, $fd, $form_id) {
        echo "close\n";
    }
    
}
