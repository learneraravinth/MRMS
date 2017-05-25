"use strict";
var express     = require('express'),
	app         = express(),
	port = process.env.PORT || 5000,
	server = require("http").createServer(app),
	bodyParser  = require('body-parser'),
	login = require("facebook-chat-api");
	var serverIP = "127.0.0.1";
	
	app.use(bodyParser.urlencoded({ limit: '50mb',extended: true }));
	app.use(bodyParser.json());

	server.listen(port,serverIP, function() { console.log("Server listening at port %d", port); });

	app.get('/check', function (req, res) {
	  res.send("data was sent");
	});
	app.post('/sent_to_multi_user', function (req, res) {
		login({
			email: "aravinth3ece@gmail.com", password: "8973449947$"
		}, function callback (err, api) {
			if(err) return console.error(err);
		    var arUserList  = req.body.users;
		    var all_data    = req.body.all_data;
			var title 		= all_data.meeting_title;
			var agenda 		= all_data.meeting_agenda;
			var from_date   = all_data.from_date;
			var to_date     = all_data.to_date;
			var room_name   = all_data.room_name;
			//console.log(arUserList[0]);					
			for(var i=0;i<arUserList.length;i++){
				api.getUserID(arUserList[i], (err, data) => {
					if(err) return console.error(err);
					// Send the message to the best match (best by Facebook's criteria)
					var msg = "";
					msg += "Hi you have a meeting in ,"+room_name+"\n title :"+title+" \n Agenda : "+agenda+"\n Start Time : "+from_date+" \n End Time : "+to_date;
					var threadID = data[0].userID;
					api.sendMessage(msg, threadID);	
					//res.send(msg);
					console.log(threadID);
				});
				res.end("successfull...");
			}
		});
	});

	app.get('/sent', function (req, res) {
		login({email: "aravinth@2ntkh.com", password: "2ntkh.aravinth"}, function callback (err, api) {
			if(err) return console.error(err);
			var yourID = 100002912068415;
			var msg = {body: "Hey!"};
			api.sendMessage(msg, yourID);
			res.end("successfull...");
		});
	});
