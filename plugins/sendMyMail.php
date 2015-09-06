<?php
//mail sending function, this could format the emails to be significantly more flashy or whatever.
//mail functionality could be used to send all kinds of alerts

function sendMyMail($fromEmail, $toEmail, $fromName, $body, $title) {
	
	$subject = $title;
	
	$headers = 'From: ' . $fromName . "\r\n" .
		'Reply-To: ' . $fromEmail . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	return mail($toEmail, $subject, $body, $headers);
}?>