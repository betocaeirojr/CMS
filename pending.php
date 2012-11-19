<?php
require_once 'conn.php';
require_once 'includes/header.php';
$a_artTypes = array("Pending" => "submitted", "Published" => "published" );

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
			" ORDER BY title";

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
					htmlspecialchars($row['title']) . "</a> ($artvalue " . date("F j, Y", strtotime($row['date_'.$artvalue])) . ")<br>\n";
		}
	}
	
	echo " </div>\n";
	
	echo "</p>\n";
}
require_once 'includes/footer.php';
mysql_free_result($result);
?>