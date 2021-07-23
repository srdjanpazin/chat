<?php
if(!isset($_COOKIE['userId'])) {
	header('Location: ./login/index.html', true, 302);
	exit;
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='utf-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel='stylesheet' href='../src/css/style.css'>
	<script>
		const user = {};
		user.id = '<?php echo $_COOKIE['userId']; ?>';
	</script>
</head>
<body>

<div class="root">
	<main>
		<?php echo '<h2>Welcome '.$_COOKIE['userId'].'</h2>'; ?>
		<p>Click on a contact name in the sidebar to start chatting.</p>
		<div id='chats-container'></div>
	</main>
	<aside id='contacts-sidebar'>
		<div class="sidebar-head">Contacts</div>
	</aside>
	<div class='chat-btn'>
		<div>Chat</div>
	</div>
</div>

<script src="../src/js/chat.js"></script>
<script src="../src/js/main.js"></script>

</body>
</html>