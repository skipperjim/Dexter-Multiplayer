var app = require('http').createServer(handler),
	io = require('socket.io').listen(app),
	fs = require('fs'),
	port = 3700;

app.listen(port, function(){
	console.log("Listening on *:"+port);
});

var playerlocation = 0;
var playerlist = [];
var creaturecount = 0;
var creaturelist = [];
clientids = [];

function zombie(){
	this.health = 10;
	this.target = "";
	this.velx = 0;
	this.vely = 0;
	this.positionx = 196;
	this.positiony = 98;
	this.name = "";
}
/*setInterval(function(){
	creatureActive(); // Moves the creatures every 3 seconds
}, 3000);
setInterval(function(){
	spawnCreature(); // Spawns a new creature every 6 seconds
}, 6000);*/

function handler(req, res){
	console.log("Handler() Called");
	fs.readFile(__dirname + '+/index.html', function(err, data){
		if(err){
			res.writeHead(500);
			return res.end('Error loading index.html');
		}
		res.writeHead(200);
		res.end(data);
	});
};

// Manage the listeners & events on the io.server
io.sockets.on('connection', function(socket){
	console.log("A new player connected. ("+socket.playername+")");
	socket.emit('message', { message: 'Welcome to the chat.' });

	socket.on('moveplayer', function(destinationx, destinationy, gamename){
		//console.log("Moving player..");
		socket.broadcast.emit('playermove', destinationx, destinationy, gamename);
	});

	socket.on('resyncplayer', function(playerx, playery, gamename){
		console.log("Resyncing player..");
		socket.broadcast.emit('syncplayer', playerx, playery, gamename);
	});

	socket.on('playerattacking', function(direction, xadd, yadd, attackangle, gamename){
		console.log("Player attacking..");
		socket.broadcast.emit('playerattacked', direction, xadd, yadd, attackangle, gamename);
	});

	socket.on('initializeplayer', function(newplayername, playerx, playery){
		console.log("Player ("+newplayername+") initialized..");
		socket.clientname = newplayername;
		playerlist.push(newplayername);
		clientids.push(socket.id);
		socket.broadcast.emit('addplayer', playerlist, newplayername, playerx, playery);
	})

	socket.on('spawnbullet', function(weapontype, gamename){
		socket.broadcast.emit('spawnclientbullet', weapontype, gamename);
	});

	socket.on('syncdcreatures', function(creatures){
		// TODO
	});

	socket.on('disconnect', function(){
		console.log("Player ("+socket.clientname+") disconnected..");
		io.sockets.emit('killplayer', socket.clientname);
		delete playerlist[socket.clientname];
		delete clientids[socket.id];
		for(var i in playerlist){
			if(playerlist[i] == socket.clientname){
				playerlist.splice(i, 1);
			}
		}
		for(var i in clientids){
			if(clientids[i] == socket.id){
				clientids.splice(i, 1);
			}
		}
		socket.broadcast.emit('message', socket.clientname);
	});	

});

function creatureActive(){
	for(var i = 0; i < creaturelist.length; i++){
		var ismove = Math.floor(Math.random()*8);
		if(ismove < 4){creaturelist[i].velx = 0; creaturelist[i].vely = 0; creaturelist[i].animation = "idle"}
			else if(ismove < 5){creaturelist[i].velx = 50; creaturelist[i].animation = "run"}
			else if(ismove < 6){creaturelist[i].velx = -50; creaturelist[i].animation = "run"}
			else if(ismove < 7){creaturelist[i].vely = 50; creaturelist[i].animation = "jump"}
			else if(ismove < 8){creaturelist[i].vely = -50; creaturelist[i].animation = "fall"}
		else{creaturelist[i].velx = 0; creaturelist[i].vely = 0; creaturelist[i].animation = "idle"}
	}
	io.sockets.emit('creaturemove', creaturelist);
};

function spawnCreature(){
	if(creaturecount < 10){
		var namerand = Math.floor(Math.random()*9999);
		var creaturename = "creature" + namerand;
		creaturename = new zombie();
		creaturecount = creaturecount + 1;
		creaturename.name = "creature" + namerand;
		creaturelist.push(creaturename);
		io.sockets.emit('spawncreature', 500, 500, creaturename.name);
		io.sockets.socket(clientids[0]).emit('resynccreatures');
	}
}