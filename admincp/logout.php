<?php
define('TRUNKSJJ', true);
ob_start();
session_start();
@include("../includes/configurations.php");
include("../includes/functions.php");
session_destroy();
header("Location: ./");
?>
