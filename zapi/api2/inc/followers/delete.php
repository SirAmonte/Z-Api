<?php
// --- Unfollow een user 

// Zijn de nodige parameters meegegeven in de request?
check_required_fields(["follower_id", "following_id"]);

if(!$stmt = $conn->prepare("delete from followers where follower_id = ? and following_id = ?")){ // " and PR_ID > 231" -> opzettelijke beperking in de versie op stevenop.be
	die('{"error":"Prepared Statement failed on prepare","errNo":' . json_encode($conn -> errno) .',"mysqlError":' . json_encode($conn -> error) .',"status":"fail"}');
}

// bind parameters ( s = string | i = integer | d = double | b = blob )
if(!$stmt -> bind_param("ii", $postvars['follower_id'], $postvars['following_id'])){
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
die('{"data":"ok","message":"Record deleted successfully","status":200}');
?>