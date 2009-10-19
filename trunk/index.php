<?php 

if (file_exists("config/common.inc.php")) {
	header("Location: users/"); 
}
else {
	header("Location: config/");
}
exit;

?>