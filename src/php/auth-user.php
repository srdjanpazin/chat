<?php
header('Content-Type: text/plain');
$mysqli = new mysqli('localhost', 'srdjan', 'eliyahu', 'chat_app');
if ($mysqli->connect_errno) {
	http_response_code(500);
	echo 'Connect Error: '.$mysqli->connect_error.'<br>'."\n";
	exit();
}

$email = $_POST['email'];
$pwd = $_POST['pwd'];
$res = $mysqli->query('SELECT id FROM users WHERE email="'.$email.
	'" AND pwd="'.$pwd.'"');

if (!$res) {
	http_response_code(500);
	echo 'auth-user.php: mysqli query failed.';
	exit;
}

if ($res->num_rows == 0) {
	http_response_code(403);
	echo 'auth-user.php: Invalid email or password.';
	exit;
}

echo $res->fetch_array(MYSQLI_NUM)[0];

$res->close();
$mysqli->close();
?>