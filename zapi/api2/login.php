<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, PUT'); // gebruik hier het meest toepasselijke HTTP verb. POST is hier ok
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

define ('INDEX', true); // 'geheime' variabele om te testen in dbcon.php en base.php : daar wordt gefaald als deze ontbreken
// --- Step 0 : connect to db
require 'inc/dbcon.php';
require 'inc/base.php';

// check required fields

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require 'inc/login/post.php';
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
	require 'inc/login/update.php';
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	require 'inc/error/400.php';
	exit;
}
?>