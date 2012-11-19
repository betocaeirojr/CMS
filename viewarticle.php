<?php
require_once 'conn.php';
require_once 'includes/outputfunctions.php';
require_once 'includes/header.php';

outputStory($_GET['article']);

showComments($_GET['article'], TRUE);

require_once 'includes/footer.php';

?>