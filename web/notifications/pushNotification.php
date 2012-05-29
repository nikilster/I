<?php

function sendPush($message, $deviceToken)
{
	//Whether to use the real push notification system or use the sandbox
	//Yes = real system
	//No = sandbox
	$PRODUCTION = true;


	// Put your device token here (without spaces):
	//$deviceToken = 'a01ac56696310c505692a6124d0c9642654666ee179d2d0a9ac227e85b98b515';

	// Put your alert message here:
	//$message = "Hi Steve!";

	// Put your private key's passphrase here:
	$passphrase = 'tara';

	if($PRODUCTION)
		$gateway = 'ssl://gateway.push.apple.com:2195';
	else
		$gateway = 'ssl://gateway.sandbox.push.apple.com:2195';

	////////////////////////////////////////////////////////////////////////////////

	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', 'Nikil Life Production.pem');
	stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

	// Open a connection to the APNS server
	$fp = stream_socket_client(
		$gateway, $err,
		$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

	if (!$fp)
		exit("Failed to connect: $err $errstr" . PHP_EOL);

	echo 'Connected to APNS' . PHP_EOL;

	// Create the payload body
	$body['aps'] = array(
		'alert' => $message,
		'sound' => 'default'
		);

	// Encode the payload as JSON
	$payload = json_encode($body);

	// Build the binary notification
	$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

	// Send it to the server
	$result = fwrite($fp, $msg, strlen($msg));

	if (!$result)
		echo 'Message not delivered' . PHP_EOL;
	else
		echo 'Message successfully delivered' . PHP_EOL;

	// Close the connection to the server
	fclose($fp);
}