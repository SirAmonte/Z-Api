<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET'); // gebruik hier het meest toepasselijke HTTP verb. GET zou hier beter zijn ...
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define ('INDEX', true);
// --- Step 0 : connect to db
require 'inc/dbcon.php';
require 'inc/base.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	require 'inc/time/get.php';
	exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require 'inc/error/400.php';
	exit;
}

?>