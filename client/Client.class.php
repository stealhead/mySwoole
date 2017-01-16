<?php
namespace Home\Lib;

class Client{
    private $_client;
    private $_conf;

    public function __construct()
    {
        if(!$this->_client instanceof swoole_client){
            $this->_client = new \Swoole\Client(SWOOLE_SOCK_TCP);
            $this->_client->connect("114.215.154.164", 9504, 0.5);
        }

    }

    public function send($data)
    {
        $this->_client->send($data);
    }

    public function recv()
    {
        return $this->_client->recv();
    }

    public function swClient()
    {
        echo __FILE__;
    }

    public function __destruct()
    {
        $this->_client->close();
    }
}
