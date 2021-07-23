'use strict';
class Chat {
	id;
	user;
	encodedUserName;
	encodedChatId;
	element;
	chatBody;
	queryCount = 0;
	optionsActive = false;
	lastMsgId;

	constructor(chatId, user) {
		this.id = chatId;
		this.user = user;
		this.encodedChatId = encodeURIComponent(chatId);
		this.encodedUserName = encodeURIComponent(user);
	}

	async createPopupChat(friendFullName) {
		let chatTop, chatBody, chatBottom, chatOptBtn, chatOpts, minBtn, closeBtn, sendBtn;
		this.element = document.createElement('div');

		this.element.className = 'chat';
		this.element.id = this.id;

		this.element.innerHTML = `
			<div class='chat-top'>
			<button class='chat-opt-btn btn'>${friendFullName}</button>
			<div class='chat-top-btn-group'>
			<button class='chat-top-sbtn btn'>_</button><button class='chat-top-sbtn btn'>X</button>
			</div>
			</div>
			<div class='chat-center'></div>
			<div class='chat-bottom'>
			<input type='text' id='type'>
			<button class='submit'>Send</button>
			</div>
			<ul class='chat-options ul' style='display:none;'>
			<li>Open in Messenger</li>
			<li>View Profile</li>
			<hr>
			<li>Color</li>
			<li>Emoji</li>
			<li>Nicknames</li>
			<hr>
			<li>Create group</li>
			<hr>
			<li>Mute conversation</li>
			<li>Ignore messages</li>
			<li>Block</li>
			<hr>
			<li>Delete conversation</li>
			<li>Something's wrong</li>
			</ul>`;
		chatsContainer.append(this.element);
		this.chatBody = this.element.children[1];

		[chatTop, chatBody, chatBottom, chatOpts] = this.element.children;
		
		chatTop.addEventListener('click', (e) => {
			e.stopPropagation();     // Stop propagation to the click event on body element
			this.element.style.height = '';
			chatBottom.style.display = '';
			chatBody.style.display = '';
		});

		chatOptBtn = chatTop.firstElementChild;

		chatOptBtn.addEventListener('click', (e) => {
			e.stopPropagation();
			if (!this.optionsActive) {
				chatOpts.style.display = '';
				this.optionsActive = true;
			} else {
				chatOpts.style.display = 'none';
				this.optionsActive = false;
			}
		});

		[minBtn, closeBtn] = chatTop.lastElementChild.children;

		minBtn.addEventListener('click', (e) => {
			e.stopPropagation();
			if (this.optionsActive)
				chatOpts.style.display = 'none';

			chatBody.style.display = 'none';
			chatBottom.style.display = 'none';
			this.element.style.height = chatTop.offsetHeight + 'px';
		});

		closeBtn.addEventListener('click', (e) => {
			e.stopPropagation();
			this.element.style.display = 'none';
			chatBtn.style.display = '';
			chatBtn.style.display = '';
		});

		sendBtn = chatBottom.children[1];
		this.inputEl = sendBtn.previousElementSibling;

		sendBtn.addEventListener('click', () => {
			this.sendMessage(this.inputEl.value);
			this.inputEl.value = '';
		})

		await this.getMessages();
	}


	/**
	 * Poll the server for new messages using long polling. The server shall
	 * respond with a new message when one is available.
	 */
	async getIncomingMessages() {
		let res;
		const url = new URL('../src/php/long-poll.php');
		url.searchParams.append('user', user.id);
		url.searchParams.append('lmid', this.lastMsgId);
		console.log(url.href);

		while(1) {
			try {
				res = await fetch(url.href, {
					method: 'GET'
				});
			} catch(err) {
				console.log('Network error: ' + err);
				return 1;
			}

			if(res.status == 502) {
				console.log('timeout');
				console.log(res.text());
				continue;
			}
			if(!res.ok) {
				console.log(res.text());
				console.log(res.statusText);
				return 1;
			} else break;
		}
		
		//const msgEl = document.createElement('div');
		//msgEl.innerHTML = text;
		//this.element.children[1].append(msgEl);
		//msgEl.scrollIntoView();   // Won't scroll to the end of the message
		this.chatBody.innerHTML += await res.text();
		this.chatBody.lastElementChild.scrollIntoView();
		this.lastMsgId = this.chatBody.lastElementChild.id.slice(1);

		return 0;
	}


	async getMessages() {
		let res;
		try {
			res = await fetch('../src/php/get-messages.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				body: 'chat_id=' + this.encodedChatId + '&user=' + this.encodedUserName +
					'&query_count=' + this.queryCount++
			});

		} catch(err) {
			console.log(err);
			return;
		}

		if(!res.ok) {
			console.log('HTTP Error ' + res.status + ': ' + await res.text());
			return;
		}
	
		this.chatBody.innerHTML += await res.text();
		if (this.chatBody.childElementCount) {
			this.chatBody.lastElementChild.scrollIntoView();
			this.lastMsgId = this.chatBody.lastElementChild.id.slice(1);
		}

	}

  /**
	 * Sends the provided message to the database and displays it in the chat
   * window.
   */
	async sendMessage(msg) {
		let res;

		if (msg === '') return;
		const msgEl = document.createElement('div');
		msgEl.className = 'msg-cont';
		msgEl.innerHTML = '<div class="flex-padding"></div><div class="msg-right msg">' +
			msg + '</div>';
		this.element.children[1].append(msgEl);
		msgEl.scrollIntoView();
		
		msg = encodeURIComponent(msg).replace(/%20/g, '+');
		
		try {
			res = await fetch('../src/php/send-message.php', {
				method: 'POST',
				headers: {
					"Content-Type": "application/x-www-form-urlencoded"
				},
				body: "chat=" + this.encodedChatId + "&from=" + this.encodedUserName +
					"&msg=" + msg
			});

		} catch(err) {
			console.log(err);
			return;
		}

		if (!res.ok) {
			console.error('Error in send-message.php: ' + await res.text());
			return;    				// remove if needed
		}
	}
}