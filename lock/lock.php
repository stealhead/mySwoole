<?php
$lock = new swoole_lock(SWOOLE_MUTEX);
echo "[Master] create lock\n";
$lock->lock();
if(pcntl_fork() > 0) {
    echo "sleep 3 first\n";
    sleep(10);
    echo "master first release lock\n";
    $lock->unlock();
} else {
    echo "[Child] wait lock\n";
    $lock->lock();
    sleep(10);
    echo "[Child] get Lock\n";
    $lock->unlock();
    exit("[Child] exit\n");
}
echo "[Master] second release lock\n";
unset($lock);
echo "sleep 3 second\n";
sleep(10);
echo "[Master]exit\n";
