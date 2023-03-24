<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport"
		content="width=device-width, initial-scale=1.0" />
	<title>QR Code Generator</title>

	<style>
		h1, h3 {
		color: green;
		}
		body, header {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		}
	</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>

<body>

    <h1>GeeksforGeeks</h1>
    <h3>QR code generator using qrcode.js</h3>
    <h3>To visit geeksforgeeks.org scan below code</h3>
    <div style="padding: 20px; background-color: blue; border:1px solid red;">
        <div id="qrcode"></div>
    </div>

	<script>
		var qrcode = new QRCode("qrcode", "https://www.geeksforgeeks.org");
	</script>
</body>

</html>
