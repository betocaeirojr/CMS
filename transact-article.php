<?php
session_start();
require_once 'conn.php';
require_once 'includes/http.php';
require_once 'includes/commons.php';

if (isset($_REQUEST['action'])) 
{
	switch ($_REQUEST['action']) 
	{
		case 'Submit New Article':
			if 	(isset($_POST['title'])
				and isset($_POST['body'])
				and isset($_SESSION['user_id']))
			{
				$sql = 	"INSERT INTO cms_articles " 						.
						"(title,body, author_id, date_submitted) " 			.
						"VALUES ('" . htmlspecialchars($_POST['title'],ENT_QUOTES)	.
						"','" . htmlspecialchars($_POST['body'], ENT_QUOTES)		.
						"'," . $_SESSION['user_id'] . ",'" 					.
						date("Y-m-d H:i:s", time()) . "')";

				//echo "DEBUG:: <BR>";
				//echo "DEBUG:: This insert SQL is: $sql <BR>";
				//echo "DEBUG:: Decoded string is: " . urldecode($sql) . "...<BR>" ; 
				 mysql_query($sql, $conn)
					or die('Could not submit article; ' . mysql_error());
			}

			redirect('index.php');
			
			break;

		case 'Edit':
			
			redirect('compose.php?a=edit&article=' . $_POST['article']);
			
			break;

		case 'Save Changes':
			if 	(isset($_POST['title'])
				and isset($_POST['body'])
				and isset($_POST['article']))
			{
				$sql = 	"UPDATE cms_articles " 											.
						"SET title='" . htmlspecialchars($_POST['title'], ENT_QUOTES) 	.
						"', body='" . htmlspecialchars($_POST['body'], ENT_QUOTES) 		. 
						"', date_submitted='" . date("Y-m-d H:i:s", time()) . "' " 		.
						"WHERE article_id=" . $_POST['article'];

				if (isset($_POST['authorid'])) 
				{
					$sql .= " AND author_id=" . $_POST['authorid'];
				}
			
				mysql_query($sql, $conn)
					or die('Could not update article; ' . mysql_error());
			}
			
			if (isset($_POST['authorid'])) 
			{
				redirect('cpanel.php');
			} else 
			{
				redirect('pending.php');
			}
			
			break;

		case 'Publish':
			if ($_POST['article']) 
			{
				$sql = 	"UPDATE cms_articles " 					.
						"SET is_published=1, date_published='" 	.
						date("Y-m-d H:i:s",time()) . "' " 		.
						"WHERE article_id=" . $_POST['article'];
				
				$isPublished = mysql_query($sql, $conn)
					or die('Could not publish article; ' . mysql_error());

				if ($isPublished)
				{
					// Retrieve Authors name and email
					$authorsinfoSQL = 	"SELECT u.name, u.email, u.notification,  a.title " . 
										"FROM cms_users u, cms_articles a " . 
										"WHERE a.article_id=" . $_POST['article'] . " AND " . 
										"u.user_id = a.author_id" . " AND " . 
										"u.notification = 1";

					$ai_result = mysql_query($authorsinfoSQL, $conn)
						or die("Could not retrive Authors info for notification...". mysql_error());

					$ai_row = mysql_fetch_assoc($ai_result);

					if ((mysql_num_rows($ai_result)<1) OR (mysql_num_rows($ai_result)>2))
					{
						// Consulta nao retornou nada ou retornou mais de 1 resultado possivel
						redirect('index.php');
						//echo "DEBUG:: Deu ZICA!! <BR>";

					} else
					{
						// Retornou um valor
						// Entao notifica o author que o artigo foi publicado
						// DEBUG
						echo "<PRE>";
						print_r($ai_row);
						echo "</PRE>";
						$email_from = "root@localhost";
						$email_subject = "Your Article has been published!";
						$mail_text = "Your article, titled: '" . htmlspecialchars_decode($ai_row['title'], ENT_QUOTES) . "' has been published. Please visit our site to check it out!";
						
						//$mail_sent = mail($email_from, $ai_row['email'], $email_subject, $mail_text);
						$mail_sent = sendmail($email_from, $ai_row['email'], $email_subject, $mail_text);

						if (!$mail_sent)
						{
							// log error and mail the webmaster
							echo "DEBUG:: Email NOT SENT";
						} /*else
						{
							echo "DEBUG:: Email SENT";
						}*/
					}
				}
			}
			
			redirect('pending.php');
			
			break;

		case 'Retract':
			if ($_POST['article']) 
			{
				$sql = "UPDATE cms_articles " 				.
				"SET is_published=0, date_published='' " 	.
				"WHERE article_id=" . $_POST['article'];
				
				mysql_query($sql, $conn)
					or die('Could not retract article; ' . mysql_error());
			}
			
			redirect('pending.php');
			
			break;

		case 'Delete':
			if ($_POST['article']) 
			{
				$sql = 	"DELETE FROM cms_articles " .
						"WHERE is_published=0 " .
						"AND article_id=" . $_POST['article'];

				mysql_query($sql, $conn)
					or die('Could not delete article; ' . mysql_error());
			}
			
			redirect('pending.php');
			
			break;

		case 'Submit Comment':
			if 	(isset($_POST['article'])
				and $_POST['article']
				and isset($_POST['comment'])
				and $_POST['comment'])
			{
				$sql = 	"INSERT INTO cms_comments " 						.
						"(article_id,comment_date,comment_user,comment) " 	.
						"VALUES (" . $_POST['article'] . ",'" 				.
						date("Y-m-d H:i:s", time()) 						.
						"'," . $_SESSION['user_id'] 						.
						",'" . htmlspecialchars($_POST['comment'], ENT_QUOTES) . "')";
				
				mysql_query($sql, $conn)
					or die('Could add comment; ' . mysql_error());
			}
			
			redirect('viewarticle.php?article=' . $_POST['article']);
			
			break;
		
		case 'Remove':
			if 	(isset($_GET['article'])
				and isset($_SESSION['user_id']))
			{
				$sql = 	"DELETE FROM cms_articles " .
						"WHERE article_id=" . $_GET['article'] .
						" AND author_id=" . $_SESSION['user_id'];
				
				mysql_query($sql, $conn)
					or die('Could not remove article; ' . mysql_error());
			}
			
			redirect('cpanel.php');
			
			break;
	}
} else 
{
	redirect('index.php');
}
?>