<?php
	require_once 'conn.php';
	require_once 'includes/header.php';
	
	$sql = 	"SELECT name, email, age, bio, notification " .
			"FROM cms_users " .
			"WHERE user_id=" . $_SESSION['user_id'];
	
	$result = mysql_query($sql, $conn)
		or die('Could not look up user data; ' . mysql_error());
	
	$user = mysql_fetch_array($result);
?>
<form method="post" action="transact-user.php">
	<p>Name:<br>
		<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
	</p>
	<p>Email:<br>
		<input type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
	</p>
	<p>Age:<br>
		<input type="text" id="age" name="age" value="<?php echo htmlspecialchars($user['age']); ?>">
	</p>
	<p>Bio:<br>
		<textarea id="age" name="bio" rows="10" cols="60"><?php echo htmlspecialchars_decode($user['bio'], ENT_QUOTES); ?> </textarea>
	</p>
	<p>
		Notify on Post Approval: <br>
<?php 
		$notify = $user['notification'];
	if ($notify == 1) 
	{
		// Notification on post approval ON
		echo "<input type=\"radio\" name=\"notify\" value=\"1\" checked> Yes<br>";
		echo "<input type=\"radio\" name=\"notify\" value=\"0\"> No<br>";
	} else 
	{
		// Notification on post approval OFF
		echo "<input type=\"radio\" name=\"notify\" value=\"1\"> Yes<br>";
		echo "<input type=\"radio\" name=\"notify\" value=\"0\" checked> No<br>";
	}
?>
</p>
	<p>
		<input type="submit" class="submit" name="action" value="Change my info">
	</p>
</form>

<h2>Pending Articles</h2>
	<div class=”scroller”>
	<table>
<?php
	$sql = 	"SELECT article_id, title, date_submitted " .
			"FROM cms_articles " .
			"WHERE is_published=0 " .
			"AND author_id=" . $_SESSION['user_id'] . " " .
			"ORDER BY date_submitted";

	$result = mysql_query($sql, $conn)
		or die('Could not get list of pending articles; ' . mysql_error());
	
	if (mysql_num_rows($result) == 0) 
	{
		echo " <em>No pending articles available</em>";
	} else 
	{
		while ($row = mysql_fetch_array($result)) 
		{
			echo "<tr>\n";
			echo '<td><a href="reviewarticle.php?article=' . $row['article_id'] . '">' . 
			htmlspecialchars($row['title']) . "</a> (submitted " . 
			date("F j, Y", strtotime($row['date_submitted'])) . ")</td>\n";
			echo "</tr>\n";
		}
	}
?>
	</table>
	</div>

<br>
<h2>Published Articles</h2>
	<div class="scroller">
	<table>
<?php
	$sql = 	"SELECT article_id, title, date_published " .
			"FROM cms_articles " .
			"WHERE is_published=1 " .
			"AND author_id=" . $_SESSION['user_id'] . " " .
			"ORDER BY date_submitted";

	$result = mysql_query($sql, $conn)
		or die('Could not get list of pending articles; ' . mysql_error());

	if (mysql_num_rows($result) == 0) 
	{
		echo " <em>No published articles available</em>";
	} else 
	{
		while ($row = mysql_fetch_array($result)) 
		{
			echo "<tr>\n";
			echo 	'<td><a href="viewarticle.php?article=' . $row['article_id'] . '">' . 
					htmlspecialchars($row['title']) . "</a> (published " .
					date("F j, Y", strtotime($row['date_published'])) .
					")</td>\n";
			echo "</tr>\n";
		}
	}
?>
</table>
</div>
<br>
<?php 
	require_once 'includes/footer.php'; 
	mysql_free_result($result);
?>
