<?php
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