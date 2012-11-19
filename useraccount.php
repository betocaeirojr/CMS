<?php
	require_once 'conn.php';

	/*
	echo "DEBUG:: ARRAY GET";
	echo "<PRE>";
	print_r($_GET);
	echo "</PRE>";

	echo "DEBUG:: ARRAY POST";
	echo "<PRE>";
	print_r($_POST);
	echo "</PRE>";

	echo "DEBUG:: ARRAY SESSION";
	echo "<PRE>";
	print_r($_SESSION);
	echo "</PRE>";
	*/

	// Setting Up Variables
	$userid = '';
	$name = '';
	$email = '';
	$password = '';
	$accesslvl = '';
	$password_hint = '';
	$age = '';
	$bio = '';
	

	if (isset($_GET['userid'])) 
	{
		
		//echo "DEBUG:: is user_id Set?? If this prints out it is...<BR>";
		$sql = 	"SELECT * FROM cms_users WHERE user_id=" . $_GET['userid'];
		
		$result = mysql_query($sql, $conn)
			or die('Could not look up user data; ' . mysql_error());

		$row = mysql_fetch_array($result);
		
		$userid = $_GET['userid'];
		$name = $row['name'];
		$email = $row['email'];
		$accesslvl = $row['access_lvl'];
		$age = $row['age'];
		$bio= $row['bio'];
	}

	require_once 'includes/header.php';
	echo "<form method=\"post\" action=\"transact-user.php\">\n";
	// echo "The user ID is:  $userid ...<BR>";
	if ($userid) 
	{
		echo "<h1>Modify Account</h1>\n";
	} else 
	{
		echo "<h1>Create Account</h1>\n";
	}
?>

<p>
	Full name:<br>
	<input type="text" class="txtinput" name="name" maxlength="100" value="<?php echo htmlspecialchars($name); ?>">
</p>
<p>
	Email Address:<br>
	<input type="text" class="txtinput" name="email" maxlength="255" value="<?php echo htmlspecialchars($email); ?>">
</p>

<p> Age: <br>
	<input type="text" class="txtinput" name="age" maxlength="255" value="<?php echo htmlspecialchars($age); ?>">
</p>

<p> Bio:<br>
	<textarea class="bio" name="bio" rows="10" cols="60"><?php echo htmlspecialchars($bio); ?> </textarea>
</p>	

<?php
	if (isset($_SESSION['access_lvl'])
		and $_SESSION['access_lvl'] == 3)
	{
		echo "<fieldset>\n";
		echo "<legend>Access Level</legend>\n";
	
		$sql = "SELECT * FROM cms_access_levels ORDER BY access_lvl DESC";
	
		$result = mysql_query($sql, $conn)
			or die('Could not list access levels; ' . mysql_error());
	
		while ($row = mysql_fetch_array($result)) 
		{
			echo ' <input type="radio" class="radio" id="acl_' . $row['access_lvl'] . 
			'" name="accesslvl" value="' . $row['access_lvl'] . '" ';

			if ($row['access_lvl'] == $accesslvl) 
			{
				echo ' checked="checked"';
			}
			echo '>' . $row['access_name'] . "<br>\n";
		}
?>
</fieldset>

<p>
	<input type="hidden" name="userid" value="<?php echo $userid; ?>">
	<input type="submit" class="submit" name="action" value="Modify Account">
</p>

<?php 
	} else 
	{ 

?>
<p>

	Password:<br>
	<input type="password" id="passwd" name="passwd" maxlength="50">
</p>

<p>
	Password (again):<br>
	<input type="password" id="passwd2" name="passwd2" maxlength="50">
</p>

<p>
	Password Hint (this hint will be email to you if somehow you manage to lost your password):<br>
	<input type="text" id="passwd_hint" name="passwd_hint" maxlength="255">
</p>

<p>
	<input type="submit" class="submit" name="action" value="Create Account">
</p>
<?php 
	}
 ?>
</form>
<?php 
	// mysql_free_result($result);
	require_once 'includes/footer.php'; 
?>