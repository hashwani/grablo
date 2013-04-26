<?php
session_start();
require("libs/_config.php");
unset($_SESSION["user"]);
header("location: $HOME");

?>
