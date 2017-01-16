<?php
/*
* 监控是否有已经到预定时间需要推送的消息
* 有则写入实时消息队列进行推送
*/
class Dynamic
{
    private $_path = "./";
    private $_filename = "text.log";

    //测试多进程写入文件
    public function inputLog ()
    {
        if(!is_dir($this->_path))
        mkdir($this->_path, 0777, true);
        file_put_contents($this->_path . $this->_filename, 'wo le ge da cao' . rand(100,999) . PHP_EOL, FILE_APPEND); 
    }
    public function setPath ($path)
    {   
        if($path) $this->_path = $path;    
    } 
    public function setFilename ($filename)
    {
        if($filename) $this->_filename = $filename;
    }
}
