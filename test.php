<?php
require './vendor/autoload.php';
use IcecastAuth\IcecastAuth;
$iceAuth = new IcecastAuth();
$iceAuth->setAuthCallback(array(new stdClass(),'toto'));
?>
