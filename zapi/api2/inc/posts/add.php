<?php
// --- "add" een post  

// Zijn de nodige parameters meegegeven in de request? 
check_required_fields(["account_id", "content", "has_image"]);

// create prepared statement
if(!$stmt = $conn->prepare("insert into posts (account_id, content) values (?,?)")){
	die('{"error":"Prepared Statement failed on prepare","errNo":' . json_encode($conn -> errno) .',"mysqlError":' . json_encode($conn -> error) .',"status":"fail"}');
}

// bind parameters ( s = string | i = integer | d = double | b = blob )
if(!$stmt -> bind_param("is", htmlentities($postvars['account_id']), $postvars['content'])){
	die('{"error":"Prepared Statement bind failed on bind","errNo":' . json_encode($conn -> errno) .',"mysqlError":' . json_encode($conn -> error) .',"status":"fail"}');
}
$stmt -> execute();

if($conn->affected_rows == 0) {
	// add failed
	$stmt -> close();
	die('{"error":"Prepared Statement failed on execute : no rows affected","errNo":' . json_encode($conn -> errno) .',"mysqlError":' . json_encode($conn -> error) .',"status":"fail"}');
}

// wat was de laatst toegevoegde ID?
$post_id = $conn -> insert_id;

if ($postvars['has_image'] === 'true'){
   $post_image_url = "https://teomanliman.be/zapi/api2/inc/uploads/uploads/{$postvars['account_id']}-{$post_id}-profile.jpg";

   if(!$stmt = $conn->prepare("update posts set post_image = ? where post_id = ?")){
      die('{"error":"Prepared Statement failed on prepare for update","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
   }
   if(!$stmt->bind_param("si", $post_image_url, $post_id)){
       die('{"error":"Prepared Statement bind failed on update","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
   }
   $stmt->execute();
   $stmt->close();
}

// antwoord met een ok -> kijk na wat je in de client ontvangt
die('{"data":"ok","message":"Record added successfully","status":200, "post_id": ' . $post_id . '}');
?>