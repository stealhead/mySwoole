<?php
    $size  = 1 << 10;
    $sw = new swoole_table( $size );
    $sw->column('id', swoole_table::TYPE_INT, 11);
    $sw->column('name', swoole_table::TYPE_STRING, 32);
    $sw->column('time', swoole_table::TYPE_INT, 11);
    $sw->create();
    $sw->set('wanggang', array('id' => 1, 'name' => 'wanggang', 'time' => time()));
    $sw->set('jiahong', array('id' => 2, 'name' => 'jiahong', 'time' => time()));
    $sw->set('yunnan', array('id' => 3, 'name' => 'yunnan', 'time' => time()));
    echo "now have rows :" . $sw->count() . "\n";
    $keys = [];
    foreach($sw as $k => $row) {
        var_dump($row);
        $keys[] = $k;
        echo $k . "\n";
        //$sw->del($k);
    }
    foreach($keys as $key) {
        $sw->del($key);
    }
    echo "\n";
    echo "last have rows :" . $sw->count() . "\n"; 
    foreach($sw as $k => $row) {
        echo $k . "\n";
        var_dump($row);
        $sw->del($k);
    }
    echo "\n";
