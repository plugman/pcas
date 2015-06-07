<?php
header('Content-type: image/jpeg');
echo file_get_contents($_GET['s'].$_GET['url']);
?>