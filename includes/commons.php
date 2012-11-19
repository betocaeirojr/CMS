<?

function sendmail($from, $to, $subject, $body)
{
	$mail_to = $to;
	$mail_from = $from;
	$mail_subject = $subject;
	$mail_messagebody = $body;
	$boundary = "==MP_Bound_xyccr948x==";

	$headers .= "Content-type: multipart/alternative; boundary=\”$boundary\"\r\n";
	$headers .= "From: " . $mail_from . "\r\n";

	$message = "This is a Multipart Message in MIME format\n";
	$message .= "--$boundary\n";
	$message .= "Content-type: text/html; charset=iso-8859-1\n";
	$message .= "Content-Transfer-Encoding: 7bit\n\n";
	$message .= $mail_messagebody . "\n";
	$message .= "--$boundary\n";
	$message .= "Content-Type: text/plain; charset=\”iso-8859-1\"\n";
	$message .= "Content-Transfer-Encoding: 7bit\n\n";
	$message .= $mail_messagebody . "\n";
	$message .= "--$boundary--";

	$mailsent = mail($mail_to, $mail_subject, $message, $headers);

	if ($mailsent) 
	{
		return TRUE;
	} else
	{
		return FALSE;
	}

}
?>