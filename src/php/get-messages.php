<?php
$mysqli = new mysqli('localhost', 'srdjan', 'eliyahu', 'chat_app');
if ($mysqli->connect_errno) {
	echo 'Connect failed: '.$mysqli->connect_error."<br>\n";
	exit;
}

$user = $_POST['user'];		// Define user component in chat.js
$offset = $mysqli->real_escape_string($_POST['query_count']) * 20;
$query = sprintf('SELECT id, from_user, time, msg FROM %s ORDER BY id DESC LIMIT %s, 20',
	$mysqli->real_escape_string($_POST['chat_id']), $offset
);
$result = $mysqli->query($query);

if (!$result) {
	http_response_code(500);
	header('Content-Type: text/plain; charset=UTF-8');
	echo 'get-messages.php: Query failed "'.$query.'"';
}
if ($result->num_rows == 0) {
	header('Content-Type: text/plain; charset=UTF-8');
	echo 'No messages';
	exit();
}

$arr = $result->fetch_all(MYSQLI_NUM);
$last_msg_time = 0;
$now = time();
$this_day = date('l');
const SECS_IN_WEEK = 60*60*24*7;

$i = count($arr) - 1;
for ($i; $i >= 0; $i--) {  // Result object may not be returned
	$row = $arr[$i];
	$msg_type = '';
	$msg = htmlspecialchars($row[3]);
	$pad = '';
	$time_el = '';
	$curr_msg_time = strtotime($row[2]);
	$curr_msg_day = date('l');

	if ($row[1] == $user) {
		$msg_type = 'msg-right msg';
		$pad = '<div class="flex-padding"></div>';
	} else {
		$msg_type = 'msg-left msg';
	}

	if ($now - $curr_msg_time < SECS_IN_WEEK) {
		$time_format = date('F j, Y \a\t g:i A', $curr_msg_time);
	} else {
		$time_format = date('F j, Y \a\t g:i A', $curr_msg_time);
	}

	if ($curr_msg_time - $last_msg_time > 600)
		$time_el = '<div class="msg-time">'.$row[2].'</div>';

	echo '<div id="m'.$row[0].'">'.$time_el.'<div class="msg-cont">'.$pad.'<div class="'
		.$msg_type.'">'.$msg.'<div class="time-tooltip">'.$time_format.
		'</div></div></div></div>'."\n";

	$last_msg_time = $curr_msg_time;
}

$mysqli->close();
?>