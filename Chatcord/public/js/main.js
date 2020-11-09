const chatForm = document.getElementById('chat-form');
const chatMessages = document.querySelector('.chat-messages');
const roomName = document.getElementById('room-name');
const userList = document.getElementById('users');
const userUrl = window.location.href.substring(window.location.href.indexOf("/") + 2).substring(0,window.location.href.substring(window.location.href.indexOf("/") + 2).indexOf(":"));
const chatroomName = document.location.href.substring(document.location.toString().indexOf('&')).substring(document.location.href.substring(document.location.toString().indexOf('&')).indexOf("=") + 1);

// Get username and room from URL
var { username, room } = Qs.parse(location.search, {
  ignoreQueryPrefix: true
});

//Pull from URL

user1 = username.substring(0, username.indexOf("?"));
user1id= (username.substring(username.indexOf("=") + 1)).substring(0, (username.substring(username.indexOf("=") + 1)).indexOf("?"));
user2fragment = ((username.substring(username.indexOf("?") + 1)).substring((username.substring(username.indexOf("?") + 1)).indexOf("?") + 1));
user2 = user2fragment.substring(user2fragment.indexOf("=") + 1, user2fragment.indexOf("?"));
user2id = user2fragment.substring(user2fragment.indexOf("?") + 1).substring(user2fragment.substring(user2fragment.indexOf("?") + 1).indexOf("=") + 1);
username = user1;

//username = user1;

const socket = io();

var timerDone = false;

//For the Leave Room Button
function addLeaveButton(){
  const btn = document.createElement('a');
  btn.href="http://" + userUrl + "/SpeedGaming";
  btn.classList.add("btn");
  btn.classList.add("fa");
  btn.classList.add("fa-home");
  btn.classList.add("fa-2x");
  document.getElementById("here").appendChild(btn);
}
// Join chatroom
socket.emit('joinRoom', { username, room });

// Get room and users
socket.on('roomUsers', ({ room, users }) => {
  outputRoomName(room);
});

//Start timer
socket.on('startTimer', msg =>{
  console.log(msg);
  addLeaveButton()
  
  //Timer System
  const startingMinutes = 5;
  let time = startingMinutes * 60;

  const countdownEl = document.getElementById('countdown');

  interval = setInterval(updateCountdown, 1000);
  
  function updateCountdown(){
    const minutes = Math.floor(time / 60);
    let seconds = time % 60;

    seconds = seconds < 10 ? '0' + seconds : seconds;

    countdownEl.innerHTML = `${minutes}: ${seconds}`;
    time--;

    if (countdownEl.innerText ==='0: 00'){
      clearInterval(interval);
      timerDone = true;
      //Add Microphone
      const a = document.createElement('a');
        a.classList.add('fa');
        a.classList.add('fa-microphone');
        a.href="http://" + userUrl + ":3001/" + chatroomName;
        document.getElementById("chat-form").appendChild(a);
    }
  }

});

// Message from server
socket.on('message', message => {
  console.log(message);
  outputMessage(message);

  // Scroll down
  chatMessages.scrollTop = chatMessages.scrollHeight;
});

// Message submit
chatForm.addEventListener('submit', e => {
  e.preventDefault();

  // Get message text
  let msg = e.target.elements.msg.value;
  
  msg = msg.trim();
  
  if (!msg){
    return false;
  }

  // Emit message to server
  socket.emit('chatMessage', msg);

  // Clear input
  e.target.elements.msg.value = '';
  e.target.elements.msg.focus();
});

// Output message to DOM
function outputMessage(message) {
  const div = document.createElement('div');
  div.classList.add('message');
  div.classList.add('user1Message');
  const p = document.createElement('p');
  p.classList.add('meta');
  p.innerText = message.username;
  p.innerHTML += `<span>${message.time}</span>`;
  div.appendChild(p);
  const para = document.createElement('p');
  para.classList.add('text');
  para.innerText = message.text;
  div.appendChild(para);
  //Profile Picture
  if (message.username === username || message.username === user2){
    const img = document.createElement('img');
    img.classList.add('pfp');
    if(timerDone){
      if (message.username === username){
        file1= new File("uploads/" + user1id + ".jpg");
        if (file1.exists()){
          img.src = "uploads/" + user1id + ".jpg";
        }
        else{
          img.src = "uploads/profiledefault.jpg";
        }
      }
      else if (message.username === user2){
        file2= new File("uploads/" + user2id + ".jpg");
        if (file2.exists()){
          img.src = "uploads/" + user2id + ".jpg";
        }
        else{
          img.src = "uploads/profiledefault.jpg";
        }
      }
    }
    else{
      img.src = "uploads/profiledefault.jpg";
    }
    div.appendChild(img);
  }
    if (message.username === user2){
      div.classList.remove('user1Message');
      div.classList.add('user2Message');
    }
    document.querySelector('.chat-messages').appendChild(div);
  }

// Add room name to DOM
function outputRoomName(room) {
  roomName.innerText = room;
}

// Add users to DOM
function outputUsers(users) {
  userList.innerHTML = '';
  users.forEach(user=>{
    const li = document.createElement('li');
    li.innerText = user.username;
    userList.appendChild(li);
  });
 }