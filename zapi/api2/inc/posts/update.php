<?php
check_required_fields(["post_id", "account_id", "content", "has_image"]);

$post_id = $postvars['post_id'];
$account_id = $postvars['account_id'];
$content = $postvars['content'];
$has_image = $postvars['has_image'];

// $sql = "update posts set content = ? where post_id = ? and account_id = ?";
// $params = [$content, $post_id, $account_id];
// $types = "sii";

// if ($has_image === 'true' && !empty($postvars['post_image'])) {
//     $sql = "update posts set content = ?, post_image = ? where post_id = ? and account_id = ?";
//     $post_image = $postvars['post_image'];
//     $params = [$content, $post_image, $post_id, $account_id];
//     $types = "ssii";
// }

$current_image = null;
if ($stmt = $conn->prepare("select post_image from posts where post_id = ? and account_id = ?")){
   $stmt->bind_param("ii", $post_id, $account_id);
   $stmt->execute();
   $stmt->bind_result($current_image);
   $stmt->fetch();
   $stmt->close();
}

if ($has_image === 'false'){
   $sql = "update posts set content = ?, post_image = NULL where post_id = ? and account_id = ?";
   $params = [$content, $post_id, $account_id];
   $types = "sii";
} else if ($has_image === 'true' && empty($current_image)){
    $post_image_url = "https://teomanliman.be/zapi/api2/inc/uploads/uploads/{$account_id}-{$post_id}-profile.jpg";
    $sql = "update posts set content = ?, post_image = ? where post_id = ? and account_id = ?";
   $params = [$content, $post_image_url, $post_id, $account_id];
   $types = "ssii";
}else{
   $sql = "update posts set content = ? where post_id = ? and account_id = ?";
   $params = [$content, $post_id, $account_id];
   $types = "sii";
}

if (!$stmt = $conn->prepare($sql)) {
    die('{"error":"Prepared Statement failed on prepare","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
}

if (!$stmt->bind_param($types, ...$params)) {
    die('{"error":"Prepared Statement bind failed on bind","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
}

$stmt->execute();

if ($conn->affected_rows == 0) {
    $stmt->close();
    die('{"error":"Prepared Statement failed on execute: no rows affected","errNo":' . json_encode($conn->errno) . ',"mysqlError":' . json_encode($conn->error) . ',"status":"fail"}');
}

$stmt->close();

die('{"data":"ok","message":"Post updated successfully","status":200}');
?>
