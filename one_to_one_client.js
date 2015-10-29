var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
    console.log("Connection established!");
	conn.send("init 11");
};

conn.onmessage = function(e) {
    console.log(e.data);
};

conn.send("msg 11 hello");