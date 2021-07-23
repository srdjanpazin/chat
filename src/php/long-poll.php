<?php
set_time_limit(600);

$mysqli = new mysqli('localhost', 'srdjan', 'eliyahu', 'chat_app');
if($mysqli->connect_errno) {
	echo 'Connect failed: '.$mysqli->connect_error.'<br>'."\n";
	exit;
}

$user = $_GET['user'];
$last_msg_id = $_GET['lmid'];
if(!is_numeric($last_msg_id)) {
	echo 'Type Error: Query parameter \'lmid\' must be numeric<br>'."\n";
	sleep(10);         // REMOVE THIS
	exit();
}

while(1) {
	$res = $mysqli->query('SELECT id, time, msg FROM chat_default
		WHERE id>'.$last_msg_id.' AND from_user<>"'.$user.'"');
	if($mysqli->errno) {
		echo 'Error in lpget.php: '.$mysqli->error .'<br>'."\n";
		break;
	}

	if($res == false) {
		echo 'Query returned false<br>'."\n";
		break;
	}
	
	if($res && $res->num_rows != 0) {
		while($row = $res->fetch_array(MYSQLI_NUM)) {
			$timestamp = strtotime($row[1]);
			$time_format = date('g:i A', $timestamp);
			
			echo '<div class="msg-cont" id="m'.$row[0].'"><div class="msg-left msg">'.$row[2].
				'<div class="time-tooltip>'.$time_format.'</div></div></div>'."\n";
		}
		break;
	}
	sleep(10);
}

$mysqli->close();
?>