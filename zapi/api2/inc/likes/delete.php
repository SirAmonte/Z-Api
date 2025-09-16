<?php
// --- Verwijder like 

// Zijn de nodige parameters meegegeven in de request?
check_required_fields(["account_id"]);

$post_id = $postvars['post_id'] ?? NULL;
$reply_id = $postvars['reply_id'] ?? NULL;

if ($post_id !== NULL) {
	if(!$stmt = $conn->prepare("delete from likes where account_id = ? and post_id = ?")){ // " and PR_ID > 231" -> opzettelijke beperking in de versie op stevenop.be
		die('{"error":"Prepared Statement failed on prepare","errNo":' . json_encode($conn -> errno) .',"mysqlError":' . json_encode($conn -> error) .',"status":"fail"}');
	}
	
	// bind parameters ( s = string | i = integer | d = double | b = blob )
	if(!$stmt -> bind_param("ii", $postvars['account_id'], $post_id)){
		die('{"error":"Prepared Statement bind failed on bind","errNo":' . json_encode($conn -> errno) .',"mysqlError":' . json_encode($conn -> error) .',"status":"fail"}');
	}
} elseif($reply_id != NULL) {
	if(!$stmt = $conn->prepare("delete from likes where account_id = ? and reply_id = ?")){ // " and PR_ID > 231" -> opzettelijke beperking in de versie op stevenop.be
		die('{"error":"Prepared Statement failed on prepare","errNo":' . json_encode($conn -> errno) .',"mysqlError":' . json_encode($conn -> error) .',"status":"fail"}');
	}
	
	// bind parameters ( s = string | i = integer | d = double | b = blob )
	if(!$stmt -> bind_param("ii", $postvars['account_id'], $reply_id)){
		die('{"error":"Prepared Statement bind failed on bind","errNo":' . json_encode($conn -> errno) .',"mysqlError":' . json_encode($conn -> error) .',"status":"fail"}');
	}
} else {
	die('{"error":"Post_id and reply_id was not provided.","status":"fail"}');
}

$stmt -> execute();

if($conn->affected_rows == 0) {
	// add failed
	$stmt -> close();
	die('{"error":"Prepared Statement failed on execute : no rows affected","errNo":' . json_encode($conn -> errno) .',"mysqlError":' . json_encode($conn -> error) .',"status":"fail"}');
}
// added
$stmt -> close();
die('{"data":"ok","message":"Record deleted successfully","status":200}');
?>