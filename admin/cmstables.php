<?php
require_once '../conn.php';

// Creating Table ACCESS LEVEL
$sql = <<<EOS
CREATE TABLE IF NOT EXISTS cms_access_levels (
access_lvl tinyint(4) NOT NULL auto_increment,
access_name varchar(50) NOT NULL default '',
PRIMARY KEY (access_lvl)
)
EOS;
$result = mysql_query($sql)
	or die(mysql_error());

// Populating Table ACCESS LEVEL
$sql = 	"INSERT IGNORE INTO cms_access_levels " .
		"VALUES (1,'User'), " .	
		"(2,'Moderator'), " .
		"(3,'Administrator'), " . 
		"(4, 'MasterUser')";
$result = mysql_query($sql)
		or die(mysql_error());

// Creating Table ARTICLES
$sql = <<<EOS
CREATE TABLE IF NOT EXISTS cms_articles_MyISAM (
article_id int(11) NOT NULL auto_increment,
author_id int(11) NOT NULL default '0',
is_published tinyint(1) NOT NULL default '0',
date_submitted datetime NOT NULL default '0000-00-00 00:00:00',
date_published datetime NOT NULL default '0000-00-00 00:00:00',
title varchar(255) NOT NULL default '',
body mediumtext NOT NULL,
PRIMARY KEY (article_id),
KEY IdxArticle (author_id,date_submitted),
FULLTEXT KEY IdxText (title,body)
) ENGINE=MyISAM
EOS;

$result = mysql_query($sql)
	or die("Table CMS_ARTICLES: " . mysql_error());

// Creating Table Comments
$sql = <<<EOS
CREATE TABLE IF NOT EXISTS cms_comments (
comment_id int(11) NOT NULL auto_increment,
article_id int(11) NOT NULL default '0',
comment_date datetime NOT NULL default '0000-00-00 00:00:00',
comment_user int(11) NOT NULL default '0',
comment text NOT NULL,
PRIMARY KEY (comment_id),
KEY IdxComment (article_id)
)
EOS;
$result = mysql_query($sql)
	or die(mysql_error());

// Creating Table USERS
$sql = <<<EOS
CREATE TABLE IF NOT EXISTS cms_users (
user_id int(11) NOT NULL auto_increment,
email varchar(255) NOT NULL default '',
passwd varchar(50) NOT NULL default '',
name varchar(100) NOT NULL default '',
access_lvl tinyint(4) NOT NULL default '1',
PRIMARY KEY (user_id),
UNIQUE KEY uniq_email (email)
)
EOS;
$result = mysql_query($sql)
	or die(mysql_error());


// Creating Admin User
$adminemail = "acaeiro@teckler.com";
$adminpass = "admin";
$adminname = "admin";
$sql = 	"INSERT IGNORE INTO cms_users " .
		"VALUES (NULL, '$adminemail', PASSWORD('$adminpass'), '$adminname', 3)";

$result = mysql_query($sql)
	or die(mysql_error());

// Creating Master User
$masteremail = "acaeiro@teckler.com";
$masterpass = "master";
$mastername = "master";
$sql = 	"INSERT IGNORE INTO cms_users " .
		"VALUES (NULL, '$masteremail', PASSWORD('$masterpass'), '$mastername', 4)";	

// HTML PAGE
echo "<html><head><title>CMS Tables Created</title></head><body>";
echo "CMS Tables created. Here is your initial login information:\n<BR>";
echo "<i>Admin User </i><BR>";
echo "<ul><li><strong>login</strong>: $adminemail . </li>\n";
echo "<li><strong>password</strong>:  $adminpass . </li></ul>\n";
echo "<i>Master User: </i><BR>"; 
echo "<ul><li><strong>login</strong>: $masteremail . </li>\n";
echo "<li><strong>password</strong>:  $masterpass . </li></ul>\n";
echo "<a href=\"login.php\">Login</a> to the site now.";
echo "</body></html>"
?>