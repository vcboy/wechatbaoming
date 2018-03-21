<?php
error_reporting(E_ERROR);
require_once 'phpqrcode/phpqrcode.php';
$url = urldecode($_GET["data"]);
$size = intval($_GET['size']);
$size = $size?$size:8;
QRcode::png($url,false,0,$size);
