<?php
$mysqli = new mysqli('localhost', 'srdjan', 'eliyahu', 'chat_app');
if($mysqli->connect_errno) {
	echo 'Connect error: '.$mysqli->connect_error.'<br>';
	exit();
}

$uname = $_POST['uname'];
$pwd = password_hash($_POST['pwd'], PASSWORD_BCRYPT);
if(!$pwd) {
	echo 'Hashing faliure<br>';
	exit();
}

$res = $mysqli->query('INSERT INTO user_accounts () VALUES ("'.$uname.'", "'.
	$pwd.'")');
if($mysqli->errno) {
	echo 'MySQL error: '.$mysqli->error.'<br>';
	exit();
}
$res->close();
$mysqli->close();
?>