<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define ('INDEX', true);
// --- Step 0 : connect to db
require 'inc/dbcon.php';
require 'inc/base.php';

// PUT or Update is not implemented
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require 'inc/uploads/upload.php';
	exit;
}

?>