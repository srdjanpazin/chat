<?php
$mysqli = new mysqli('localhost', 'srdjan', 'eliyahu', 'chat_app');
if ($mysqli->connect_errno) {
	http_response_code(500);
	header('Content-Type: text/plain; charset=UTF-8');
	printf("Connect failed: %s<br>\n", $mysqli->connect_error);
	exit;
}

$chat =	$mysqli->real_escape_string($_POST['chat']);
$from = $mysqli->real_escape_string($_POST['from']);
$time = date('YmdHis');
$msg = $mysqli->real_escape_string($_POST['msg']);

$query = 'INSERT INTO '.$chat.' (from_user, time, msg) VALUES
	("'.$from.'", "'.$time.'", "'.$msg.'")';

$result = $mysqli->query($query);
if (!$result) {
	http_response_code(500);
	header('Content-Type: text/plain; charset=UTF-8');
	echo 'Insert query failed'."\n";
	echo 'Query: '.$query;
}

$mysqli->close();
?>