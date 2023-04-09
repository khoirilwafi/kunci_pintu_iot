<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
</head>
<body>

    <script>

        const socket = new WebSocket('ws://172.16.0.134:6001/office-connect?app=aNmB0bkbrE1PS6K07nrt');

        socket.addEventListener('open', function (event) {
            socket.send('Hello Server!');
        });

        socket.addEventListener('message', function (event) {
            console.log('Received message from server: ', event.data);
        });

        socket.addEventListener('close', function (event) {
            console.log('Connection closed');
        });

        socket.addEventListener('error', function (event) {
            console.log('Error: ', event);
        });


    </script>

</body>
</html>
