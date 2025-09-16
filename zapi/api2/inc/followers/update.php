<?php
check_required_fields(["follower_id", "following_id", "is_blocked"]);

if(!$stmt = $conn->prepare("update followers set is_blocked = ? WHERE follower_id = ? AND following_id = ?")) {
    die('{"error":"Prepared Statement failed on prepare","errNo":' . json_encode($conn->errno) .',"mysqlError":' . json_encode($conn->error) .',"status":"fail"}');
}

if(!$stmt->bind_param("iii", htmlentities($postvars['is_blocked']), $postvars['follower_id'], $postvars['following_id'])) {
    die('{"error":"Prepared Statement bind failed on bind","errNo":' . json_encode($conn->errno) .',"mysqlError":' . json_encode($conn->error) .',"status":"fail"}');
}

$stmt->execute();

if($conn->affected_rows == 0) {
    $stmt->close();
    die('{"error":"Prepared Statement failed on execute: no rows affected","errNo":' . json_encode($conn->errno) .',"mysqlError":' . json_encode($conn->error) .',"status":"fail"}');
}

$stmt->close();
die('{"data":"ok","message":"Followers updated successfully","status":200}');
?>