<?php
    $lock = new swoole_lock(SWOOLE_FILELOCK, './lock.log');
    $lock->lock();
    sleep(30);
