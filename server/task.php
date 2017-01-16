<?php

$serv = new swoole_server("0.0.0.0", 9502);

$serv->set(
	array(
		'worker_num' => 8, 
		'task_worker_num' => 100, 
		'daemonize' => false,
		'heartbeat_check_interal' =>1,
		'heartbeat_idle_time' => 5
	));

$serv->on('Connect', function($serv, $fd){
	echo "Client:Connect.\n";
});

$serv->on('receive', function($serv, $fd, $form_id, $data){
	$start = microtime();
	echo $data . "\n";
    echo "fd : " . $fd . "  form id : " . $form_id . "\n";
	//echo $start . "\n";
	$serv->send($fd, 'i have get '. json_encode($data) . "workid $serv->worker_id \n");
	//for($i = 1; $i < 60; $i++) {
		$work_id = $serv->task($data, $fd);
	//	echo "end work_id: $work_id \n";
	//}
	$serv->send($fd, 'workerid is:' . $work_id . "\n");
	$end = microtime();
	//echo $end . "\n";
	//$serv->heartbeat(true);
	//$serv->finish("over");
});
$serv->on('task', function($serv, $task_id, $form_id, $data){
	echo "page:" . $data . "\n";
	echo microtime() . "\n";
	$obj = new CountItem();
	$array = $obj->getItems($data);
	echo microtime() . "\n";
	file_put_contents('./items.txt', $array, FILE_APPEND);
	echo microtime() . "\n";
});
$serv->on('WorkerStart', function($serv, $work_id){
	if($serv->taskworker){
		require "./curljd.php";
		echo "taskworker my id is : $work_id\n";
	}
});

$serv->on('WorkerStop', function($serv, $work_id){});
$serv->on('finish', function($serv, $task_id, $data){
	echo "task over\n";
});
$serv->on('close', function($serv, $fd){
	echo $fd."client close\n";
});
$serv->start();
//$serv->heartbeat();
