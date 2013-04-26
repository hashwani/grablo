<?php
/*** JamalSoft ***/
error_reporting(E_ALL);
// *** MySQL database config
$SQL_HOST = "localhost";
$SQL_DB = "phoenix5_grablo";
$SQL_USER = "phoenix5_jamal";
$SQL_PASS = "jamal786";

$SITE_TITLE = "Grablo";
$SITE_KEYWORDS = "Grablo, Auction, Bid Online, Buy";
$SITE_DESCRIPTION = "Grablo Online Auction Site";


$HOST = "http://" . $_SERVER["HTTP_HOST"] . "/";
$ROOT_DIR = "grablo/";
$HOME = $HOST . $ROOT_DIR;
$MEDIA_DIR = $HOME . "media/";


$ERROR_FILE = "errors.html";

?>
