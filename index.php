<?php
	require_once 'conn.php';
	require_once 'includes/outputfunctions.php';
	require_once 'includes/header.php';

	//$arr_order_article = array("byauthor" => "WHERE author")
	//$a_artTypes = array("Pending" => "submitted", "Published" => "published" );

	//if (isset($_REQUEST['o']))
	//{
	//}

   
	$sql = 	"SELECT article_id FROM cms_articles WHERE is_published=1 " .
			"ORDER BY date_published DESC";

	$result = mysql_query($sql, $conn);
	

	if (mysql_num_rows($result) == 0) 
	{
		echo " <br>\n";
		echo " There are currently no articles to view.\n";
	} else 
	{
		while ($row = mysql_fetch_array($result)) 
		{
			outputStory($row['article_id'], TRUE);
		}
	}

	mysql_free_result($result);
	mysql_close($conn);
	
	require_once 'includes/footer.php';
?>