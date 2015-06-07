<?php
/*
+--------------------------------------------------------------------------
|	admin.php
|   ========================================
|	Selects which encoding method to use
+--------------------------------------------------------------------------
*/
include '..'.DIRECTORY_SEPARATOR.'ini.inc.php';
include '..'.CC_DS.'includes'.CC_DS.'global.inc.php';

if (file_exists('..'.CC_DS.'admin.php')) {
	header('location: ../admin.php');
} else {
	header('HTTP/1.1 404 Not Found');
	header('HTTP/1.0 404 Not Found');
	header('Status: 404 Not Found');
}
?>
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL <?php echo $_SERVER['REQUEST_URI']; ?> was not found on this server.</p>
<hr>
<address><?php echo $_SERVER['SERVER_SOFTWARE']; ?></address>
</body></html>