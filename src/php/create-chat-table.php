<?php
$mysqli = new mysqli('localhost', 'srdjan', 'eliyahu', 'chat_app');
if ($mysqli->connect_errno) {
	printf("Connect failed: %s\n", $mysqli->connect_error);
	exit;
}
$result = $mysqli->query('CREATE TABLE IF NOT EXISTS '.$chatname.' (
	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	from_user VARCHAR(40) NOT NULL,
	time TIMESTAMP NOT NULL,
	msg TEXT NOT NULL)');

$mysqli->close();
?>