<?php
check_required_fields(["notification_id", "account_id", "is_read"]);

if(!$stmt = $conn->prepare("update notifications set is_read = ? where notification_id = ? and account_id = ?")) {
    die('{"error":"Prepared Statement failed on prepare","errNo":' . json_encode($conn->errno) .',"mysqlError":' . json_encode($conn->error) .',"status":"fail"}');
}

if(!$stmt->bind_param("iii", htmlentities($postvars['is_read']), $postvars['notification_id'], $postvars['account_id'])) {
    die('{"error":"Prepared Statement bind failed on bind","errNo":' . json_encode($conn->errno) .',"mysqlError":' . json_encode($conn->error) .',"status":"fail"}');
}

$stmt->execute();

if($conn->affected_rows == 0) {
    $stmt->close();
    die('{"error":"Prepared Statement failed on execute: no rows affected","errNo":' . json_encode($conn->errno) .',"mysqlError":' . json_encode($conn->error) .',"status":"fail"}');
}

$stmt->close();
die('{"data":"ok","message": Notifcations updated successfully","status":200}');
?>