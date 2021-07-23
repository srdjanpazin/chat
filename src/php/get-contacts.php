<?php
$mysqli = new mysqli('localhost', 'srdjan', 'eliyahu', 'chat_app');
if ($mysqli->connect_errno) {
	http_response_code(500);
	header('Content-Type: text/plain; charset=UTF-8');
	echo 'Database Connection Error: ' . $mysqli->connect_error;
	exit;
}

$query = sprintf('SELECT contacts FROM users WHERE id="%s"',
	$mysqli->real_escape_string($_POST['user_id']));
$result = $mysqli->query($query);

if (!$result) {
	http_response_code(500);
	header('Content-Type: text/plain; charset=UTF-8');
	echo 'get-contacts.php: Query failed "'.$query.'"';
	exit;
}

if ($result->num_rows > 0) {
	$row = $result->fetch_array(MYSQLI_NUM);
	$arr = unserialize($row[0]);

	$query = 'SELECT id, fname, lname FROM users WHERE id IN (';
	for ($i = 0; $i < count($arr)-1; $i++) {
		$query .= '"' . $arr[$i] . '",';
	}
	$query .= '"' . $arr[$i] . '")';

	$result = $mysqli->query($query);
	if (!$result) {
		http_response_code(500);
		header('Content-Type: text/plain; charset=UTF-8');
		echo 'get-contacts.php: Query failed "'.$query.'"';
		exit;

	}
	if ($result->num_rows > 0) {
		$ccc = $result->fetch_all(MYSQLI_NUM);
		header('Content-Type: application/json');
		echo json_encode($ccc);
	
	} else {
		header('Content-Type: text/plain');
		echo $query;
	}

}

$result->close();
$mysqli->close();
?>