<?
// Function to handle notifications sent by email
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

// Function to validate at the server side the info inputted by the user
// This function works over associative arrays
function arrayValidateUserData($arrayInfo)
{
	$arrayOrigin 	= $arrayInfo;
	$fieldValue 	= $arrayOrigin['Value']; 
	$fieldName 		= $arrayOrigin['Name'];
	$fieldType 		= $arrayOrigin['Type'];
	$fieldMaxLenght = $arrayOrigin['MaxLength'];
	$fieldMinLenght = $arrayOrigin['MinLength'];

	//print_r($arrayOrigin);
	//echo "<BR>";
	//echo $fieldType 		. "<BR>"	;
	//echo $fieldMaxLenght	. "<BR>"	;
	//echo $fieldMinLenght	. "<BR>"	;

	//echo "<BR> Entrando no Switch... <BR>";
	switch ($fieldType) {
		case 'Text':
			// Testing Min Length
			if (strlen($fieldValue) < $fieldMinLenght) {
				$isValidValue = 1;
				
			} else {
				$isValidValue = 0;
			
			}
			
			// Testing Max Lenght
			if (strlen($fieldValue) > $fieldMaxLenght){
				$isValidValue = $isValidValue + 1;
			
			} else {
				$isValidValue = $isValidValue + 0;

			}

			// Testing if setted
			if (!isset($fieldValue)) {
				$isValidValue = $isValidValue + 1;
			
			} else {
				$isValidValue = $isValidValue + 0;
			}
			
			//echo "The Field Value Length is: " . strlen($fieldValue) .   "<BR>";
			//echo "The Min Length for this value is: ". $fieldMinLenght . "<BR>";
			//echo "The Max Length for this value is: ". $fieldMinLenght . "<BR>";
			//echo "<BR> Inside Function to Validate User Data <BR> " . "isValidValue =  " . $isValidValue . "<BR>";

			break;
		
		default:
			# code...
			break;
	}
	return $isValidValue; 
}
?>