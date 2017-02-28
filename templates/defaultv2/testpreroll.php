<?php
require_once('plajax/haplugin/license.php');
require_once('plajax/haplugin/ha.function.php');
$ha = new HAPlugin;
?>
<html>
<head>
    <title>Test2</title>
</head>
<body>
<div style="max-width: 720px">
    <div id="player-area"><?php echo $ha->handle('https://www.youtube.com/watch?v=BNdRb9UC_qs',NULL,NULL,'video','vietnam',3);?> </div>
</div>
</body>
</html>
