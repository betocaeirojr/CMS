<?php
require_once 'conn.php';
require_once 'includes/http.php';

if 	((isset($_GET['author'])) and is_numeric($_GET['author']) and ($_GET['author']>0) and ($_GET['author']!=''))
{
	$sql = 	"SELECT name, age, bio FROM cms_users " . 
			"WHERE user_id = ". $_GET['author'] ; 

	$result = mysql_query($sql, $conn)
		or die('Could not retrieve author extented info; ' . mysql_error());

	$row = mysql_fetch_array($result);

} else
{
	redirect('index.php');
}

require_once 'includes/header.php';

echo "<h4> About the Author </h4>";

echo "<div>";
echo "<li> <b>Author's Name: </b> <BR>".  $row['name'] . " </li> <BR>";
echo "<li> <b>Author's Age : </b> <BR> " . $row['age']  . "</li><BR>";
echo "<li> <b>Author's Mini Bio : </b> <BR>" . htmlspecialchars_decode($row['bio'], ENT_QUOTES) . "</li><BR>";
echo "</div>"; 


	mysql_free_result($result);
	require_once 'includes/footer.php';

?>