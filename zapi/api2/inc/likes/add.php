<?php
// --- "add" een like  

// Zijn de nodige parameters meegegeven in de request? 
check_required_fields(["account_id"]);

$post_id = isset($postvars['post_id']) ? $postvars['post_id'] : NULL;
$reply_id = isset($postvars['reply_id']) ? $postvars['reply_id'] : NULL;

// create prepared statement
if(!$stmt = $conn->prepare("insert into likes (account_id, post_id, reply_id) values (?,?,?)")){
	die('{"error":"Prepared Statement failed on prepare","errNo":' . json_encode($conn -> errno) .',"mysqlError":' . json_encode($conn -> error) .',"status":"fail"}');
}

// bind parameters ( s = string | i = integer | d = double | b = blob )
if(!$stmt -> bind_param("iii", htmlentities($postvars['account_id']), $post_id, $reply_id)){
	die('{"error":"Prepared Statement bind failed on bind","errNo":' . json_encode($conn -> errno) .',"mysqlError":' . json_encode($conn -> error) .',"status":"fail"}');
}
$stmt -> execute();

if($conn->affected_rows == 0) {
	// add failed
	$stmt -> close();
	die('{"error":"Prepared Statement failed on execute : no rows affected","errNo":' . json_encode($conn -> errno) .',"mysqlError":' . json_encode($conn -> error) .',"status":"fail"}');
}
// added
$stmt -> close();

// wat was de laatst toegevoegde ID?
$like_id = $conn -> insert_id;

// antwoord met een ok -> kijk na wat je in de client ontvangt
die('{"data":"ok","message":"Record added successfully","status":200, "like_id": ' . $like_id . '}');
?>