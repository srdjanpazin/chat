'use strict';

const contactsSidebar = document.getElementById('contacts-sidebar');
const chatBtn = contactsSidebar.nextElementSibling;
const chatsContainer = contactsSidebar.previousElementSibling.lastElementChild;

const chats = {};
// Temporary store for chat ids. Should be moved to the database.
const users = {
	mark: {
		chats: [
			{ id: 'chat_1', with: 'johnwhite' },
			{ id: 'chat_2', with: 'paulsmith' }
		]
	}
};

// Populate contacts sidebar with contacts
let response = fetch('http://localhost/chatapp/src/php/get-contacts.php', {
	method: 'POST',
	headers: {
		'Content-Type': 'application/x-www-form-urlencoded'
	},
	body: 'user_id=' + encodeURIComponent(user.id)
})
.then(response => response.json())
.then(data => {
	for (let i = 0; i < data.length; i++) {
		const [ userId, fname, lname ] = data[i];
		// Find the ids of chats with each user from the global 'users' object
		const chatId = users[user.id].chats.forEach((obj) => {
			if (obj.with === userId) return obj.id;
		});
		const elem = document.createElement('div');
		elem.className = 'contact';
		elem.id = userId;
		elem.dataset.clicked = 0;
		elem.dataset.chatId = 'chat_' + (i + 1);
		elem.innerText = fname + ' ' + lname;
		elem.onclick = async function () {
			const id = this.dataset.chatId;
			if (this.dataset.clicked === '0') {
				chats[id] = new Chat(id, user.id);
				await chats[id].createPopupChat(this.innerText);
				this.dataset.clicked = '1';
			} else {
				chats[id].element.style.display = '';
			}
		}
		contactsSidebar.append(elem);
	}
	
})
.catch(err => {
	console.log('Fetch error: ' + err);
});

	

// Display chat window
/*chatBtn.addEventListener('click', async function(){
	this.style.display = 'none';

	if (chats.chatDefault === undefined) {
		let val;
		chats.chatDefault = new Chat('chat_default', user.id);
		await chats.chatDefault.createPopupChat('Unknown');
		console.log(chats);

		do {
			val = await chats.chatDefault.getIncomingMessages();
		} while(!val);

	} else {
		chats.chatDefault.element.style.display = '';
	}

});
*/