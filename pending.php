<?php
require_once 'conn.php';
require_once 'includes/header.php';

// Debugging
/*
echo "<PRE>";
print_r($_SESSION);
echo "</PRE>"; 
*/

$a_artTypes = array("Pending" => "submitted", "Published" => "published" );

if (($_SESSION['access_lvl']) == 2)
{
	// User is a moderator so can access all posts
	$access_lvl_sql = "";
	
} else
{
	// User is not a moderador
	$access_lvl_sql = " and author_id=" . $_SESSION['user_id'];
}

echo "<h2>Article Availability</h2>\n";
$i = -1;
foreach ($a_artTypes as $artkey => $artvalue) 
{
	$i++;
	echo "<h3>" . $artkey . " Articles</h3>\n";
	echo "<p>\n";
	echo " <div class=\"scroller\">\n";
	$sql = 	"SELECT article_id, title, date_". $artvalue .
			" FROM cms_articles " .
			"WHERE is_published=" . $i . 
			$access_lvl_sql . 
			" ORDER BY title";

	//echo "DEBUG:: The SQL is $sql... <BR>";

	$result = mysql_query($sql, $conn)
		or die('Could not get list of pending articles; ' . mysql_error());
	
	if (mysql_num_rows($result) == 0) 
	{
		echo " <em>No " . $artkey . " articles available</em>";
	} else 
	{
		while ($row = mysql_fetch_array($result)) 
		{
			echo ' <a href="reviewarticle.php?article=' . $row['article_id'] . '">' . 
					htmlspecialchars_decode($row['title'], ENT_QUOTES) . "</a> ($artvalue " . date("F j, Y", strtotime($row['date_'.$artvalue])) . ")<br>\n";
		}
	}
	
	echo " </div>\n";
	
	echo "</p>\n";
}
require_once 'includes/footer.php';
mysql_free_result($result);
?>