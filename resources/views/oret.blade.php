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

        const socket = new WebSocket('ws://127.0.0.1:6001/app/aNmB0bkbrE1PS6K07nrt');

        socket.addEventListener('open', function (event) {
            console.log('opened');
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

        // setInterval(() => {
        //     socket.send(`{"event":"pusher:ping","data":{}}`);
        // }, 30000);

        // aNmB0bkbrE1PS6K07nrt:e02dfc3e1293792acfa3c14b03d06510ef3c6cfc8a2f06ec2f57c3a7706ab24f


    </script>

</body>
</html>
