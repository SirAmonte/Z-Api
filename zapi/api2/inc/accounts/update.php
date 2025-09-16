<?php
// --- "update" een account  

// Zijn de nodige parameters meegegeven in de request?
if (isset($postvars['password']) && $postvars['password'] !== '' && isset($postvars['account_id'])) {
    $sql = "update accounts set password = ? where account_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('{"error":"Prepared Statement failed on prepare","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
    }
    $stmt->bind_param("si", $postvars['password'], $postvars['account_id']);
} else {
    $sql = "update accounts set username = ?, email = ?, profilephoto = ?, bio = ? where account_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('{"error":"Prepared Statement failed on prepare","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
    }
    $stmt->bind_param("ssbsi", $postvars['username'], $postvars['email'], $postvars['profilephoto'], $postvars['bio'], $postvars['account_id']);
}

if (!$stmt->execute()) {
    die('{"error":"Prepared Statement failed on execute","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
}

if ($conn->affected_rows === 0) {
    die('{"error":"No changes made to the record or record not found","status":"fail"}');
}

$profile_image_url = "https://teomanliman.be/zapi/api2/inc/uploads/uploads/{$postvars['account_id']}-profile.jpg";

   // Update the post with the image URL
   if(!$stmt = $conn->prepare("update accounts set profilephoto = ? where account_id = ?")){
      die('{"error":"Prepared Statement failed on prepare for update","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
   }
   if(!$stmt->bind_param("si", $profile_image_url, $postvars['account_id'])){
       die('{"error":"Prepared Statement bind failed on update","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
   }

$stmt->execute();
$stmt->close();
die('{"data":"ok","message":"Record updated successfully","status":200, "account_id": ' . $postvars['account_id'] . '}');

?>