<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>

	</style>
</head>
<body>

<h2>Signup page</h2>

<form class="login-form" action="create-user-account.php" method="post">
	<label for="uname">User name:</label><br>
	<input type="text" class="block" id="uname" name="uname" required>
	<label for="pwd">Password:</label><br>
	<input type="password" class="block" id="pwd" name="pwd" required>
	<button>Create</button>
</form>

<script>
	'use strict';
	const loginForm = document.body.querySelector('.login-form');
	const unameField = loginForm.children[2];
	const pwdFiled = loginForm.children[5];
	const loginBtn = loginForm.lastElementChild;

	unameField.addEventListener('onchange')
	loginBtn.addEventListener('click', async function(ev) {
		let res;
		const uname = loginForm.children[2].value;
		const pwd = loginForm.children[5].value;
		const unameEnc = encodeURIComponent(uname).replace(/%20/g, '+');
		const pwdEnc = encodeURIComponent(pwd).replace(/%20/g, '+');

		try {
			res = await fetch('create-user-account.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				body: 'uname=' + unameEnc + '&pwd=' + pwdEnc
			});
		} catch(err) {
			console.log('Fetch error: ' + err);
			return;
		}
	}, false);
</script>

</body>
</html>