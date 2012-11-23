<?php
require_once 'conn.php';
require_once 'includes/http.php';

/* DEBUG MODE
echo "Array REQUEST: ";
echo "<PRE>";
print_r($_REQUEST);
echo "</PRE>";

echo "Array POST: ";
echo "<PRE>";
print_r($_POST);
echo "</PRE>";
*/

if (isset($_REQUEST['action'])) 
{
	switch ($_REQUEST['action']) 
	{
		case 'Login':
			if (isset($_POST['email']) and isset($_POST['passwd']))
			{
				$sql = 	"SELECT user_id, access_lvl,name " .
						"FROM cms_users " .
						"WHERE email='" . $_POST['email'] . "' " .
						"AND passwd=PASSWORD('" . $_POST['passwd'] . "')";
				
				// echo "DEBUG:: $sql <BR><BR>";

				$result = mysql_query($sql, $conn)
					or die('Could not look up user information; ' . mysql_error());
				
				// echo "DEBUG:: $result <BR>";

				if ($row = mysql_fetch_array($result)) 
				{
					session_start();
					$_SESSION['user_id'] = $row['user_id'];
					$_SESSION['access_lvl'] = $row['access_lvl'];
					$_SESSION['name'] = $row['name'];

					/*  DEBUG MODE
 					echo "DEBUG:: Array SESSION";
					echo "<PRE>";
					print_r($_SESSION);
					echo "</PRE>"; */
				}
			}
			
			mysql_free_result($result);
			
			redirect('index.php');

			break;

		case 'Logout':
			session_start();
			session_unset();
			session_destroy();
			
			redirect('index.php');
			
			break;

		case 'Create Account':
			if 	(isset($_POST['name']) and 
				isset($_POST['email']) and 
				isset($_POST['passwd']) and 
				isset($_POST['passwd2']) and 
				$_POST['passwd'] == $_POST['passwd2'] and
				isset($_POST['passwd_hint']) and 
				(isset($_POST['age']) and (is_numeric($_POST['age']))) and
				isset($_POST['bio']))
			{
				$sql = 	"INSERT INTO cms_users (email, name, passwd, passwd_hint, age, bio, notification) " .
						"VALUES ('" . $_POST['email'] . 
							"','" . $_POST['name'] . 
							"', PASSWORD('" .  $_POST['passwd'] . "'),'". 
							htmlspecialchars($_POST['passwd_hint'], ENT_QUOTES). "'," . 
							$_POST['age'] . ",'" . 
							htmlspecialchars($_POST['bio'], ENT_QUOTES) . "', " . 
							$_POST['notify'] . 
							")";
				
				//echo "DEBUG:: $sql <BR>";

				$result = mysql_query($sql, $conn)
					or die('Could not create user account; ' . mysql_error());
				
				session_start();
				$_SESSION['user_id'] = mysql_insert_id($conn);
				$_SESSION['access_lvl'] = 1;
				$_SESSION['name'] = $_POST['name'];

				/* DEBUG MODE
				echo "DEBUG:: Array SESSION";
				echo "<PRE>";
				print_r($_SESSION);
				echo "</PRE>"; */

			}
			
			mysql_free_result($result);

			redirect('index.php');
			
			break;

		case 'Modify Account':
			if 	(isset($_POST['name'])
				and isset($_POST['email'])
				and isset($_POST['accesslvl'])
				and isset($_POST['userid']) and 
				(isset($_POST['age']) and (is_numeric($_POST['age']))) and
				isset($_POST['bio']) )
			{
				$sql = 	"UPDATE cms_users " .
						"SET email='" . $_POST['email'] .
						"', name='" . $_POST['name'] .
						"', access_lvl=" . $_POST['accesslvl'] . " , "  .
						"age = " . $_POST['age'] . "," . 
						"bio = '" . htmlspecialchars($_POST['bio'], ENT_QUOTES) .  "', " .
						"notification = " . $_POST['notify'] .  
						" WHERE user_id=" . $_POST['userid'];
				
				//echo "DEBUG:: $sql <BR>";

				mysql_query($sql, $conn)
					or die('Could not update user account; ' . mysql_error());
			}
			
			mysql_free_result($result);

			redirect('admin.php');
			
			break;

		case 'Send my reminder!':
			if 	(isset($_POST['email'])) 
			{
				$sql = 	"SELECT passwd_hint FROM cms_users " .
						"WHERE email='" . $_POST['email'] . "'";
				
				$result = mysql_query($sql, $conn)
					or die('Could not look up password; ' . mysql_error());
				
				if (mysql_num_rows($result)) 
				{
					$row = mysql_fetch_array($result);
					$subject = 'CMS Site password reminder';
					$body = "Just a reminder, your password hint for the " .
							"Comic Book Appreciation site is: " .
							$row['passwd_hint'] .
							"\n\nYou can use this to log in at http://" . $_SERVER['HTTP_HOST'] .
							dirname($_SERVER['PHP_SELF']) . '/';
			
					
					mail($_POST['email'], $subject, $body)
						or die('Could not send reminder email.');
				}
			}
			
			mysql_free_result($result);

			redirect('login.php');
			
			break;

		case 'Change my info':
			session_start();
			if 	(isset($_POST['name'])
				and isset($_POST['email'])
				and isset($_SESSION['user_id']))
			{
				$sql = 	"UPDATE cms_users " .
						"SET email='" . $_POST['email'] .
						"', name='" . $_POST['name'] . "', " .
						"age = " . $_POST['age']. "," .
						"bio = '" . htmlspecialchars($_POST['bio'], ENT_QUOTES) . "', " . 
						"notification = " . $_POST['notify'] .  
						" WHERE user_id=" . $_SESSION['user_id'];
				
				echo $sql;
				mysql_query($sql, $conn)
					or die('Could not update user account; ' . mysql_error());
			}
			
			mysql_free_result($result);

			redirect('cpanel.php');
			
			break;
	}
}
?>