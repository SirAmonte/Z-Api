<?php
check_required_fields(["post_id", "account_id", "content", "reply_id"]);

$parent_reply_id = $postvars['parent_reply_id'] ?? NULL; 

if ($parent_reply_id !== NULL) {
    if(!$stmt = $conn->prepare("update replies set content = ? where post_id = ? and account_id = ? and parent_reply_id = ?")) {
        die('{"error":"Prepared Statement failed on prepare","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
    }

    if(!$stmt->bind_param("siii", htmlentities($postvars['content']), $postvars['post_id'], $postvars['account_id'], $parent_reply_id)) {
        die('{"error":"Prepared Statement bind failed on bind","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
    }
} else {
    if(!$stmt = $conn->prepare("update replies set content = ? where post_id = ? and account_id = ? and reply_id = ?")) {
        die('{"error":"Prepared Statement failed on prepare","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
    }

    if(!$stmt->bind_param("siii", htmlentities($postvars['content']), $postvars['post_id'], $postvars['account_id'], $postvars['reply_id'])) {
        die('{"error":"Prepared Statement bind failed on bind","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
    }
}

$stmt->execute();

if($conn->affected_rows == 0) {
    $stmt->close();
    die('{"error":"Prepared Statement failed on execute: no rows affected","errNo":' . json_encode($conn->errno) .',"mysqlError":' . json_encode($conn->error) .',"status":"fail"}');
}

$stmt->close();
die('{"data":"ok","message":"Reply updated successfully","status":200}');
?>