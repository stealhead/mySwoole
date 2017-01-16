<?php
    $lock = new swoole_lock(SWOOLE_FILELOCK, './lock.log');
    //echo "trylock : " . $lock->trylock() . "\n";
    $lock->lock();
