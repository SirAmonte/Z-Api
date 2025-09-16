<?php
$postvars = $_GET; 
// https://www.w3schools.com/php/func_var_isset.asp#:~:text=This%20function%20returns%20true%20if,with%20the%20unset()%20function.

if (isset($postvars['follower_id'], $postvars['following_id'])) {
    $sql = "select follower_id
           from followers
           where following_id = ?
           and follower_id not in (
                  SELECT following_id
                  FROM followers
                  WHERE follower_id = ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        $response['code'] = 7;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $response['data'] = $conn->error;
        deliver_response($response);
        exit;
    }

    $stmt->bind_param("ii", $postvars['follower_id'], $postvars['following_id']);
    $stmt->execute();
    $result = $stmt->get_result();
}else if (isset($postvars['follower_id'])){
   $sql = "select follower.username AS follower_username,
    follower.profilephoto AS follower_photo,
    follower.is_verified AS follower_verified,
    following.username AS following_username,
    following.profilephoto AS following_photo,
    following.is_verified AS following_verified,
    f.follower_id,
    f.following_id,
    f.follow_date,
    f.is_blocked
   from followers f 
   JOIN accounts follower ON f.follower_id = follower.account_id
   JOIN accounts following ON f.following_id = following.account_id where f.follower_id = ?";
   $stmt = $conn->prepare($sql);
   if (!$stmt) {
        $response['code'] = 7;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $response['data'] = $conn->error;
        deliver_response($response);
        exit;
    }

    $stmt->bind_param("i", $postvars['follower_id']);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "select follower.username AS follower_username,
    follower.profilephoto AS follower_photo,
    follower.is_verified AS follower_verified,
    following.username AS following_username,
    following.profilephoto AS following_photo,
    following.is_verified AS following_verified,
    f.follower_id,
    f.following_id,
    f.follow_date,
    f.is_blocked
   from followers f 
   JOIN accounts follower ON f.follower_id = follower.account_id
   JOIN accounts following ON f.following_id = following.account_id";
    $result = $conn->query($sql);
}

if (!$result) {
    $response['code'] = 7;
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $response['data'] = $conn->error;
    deliver_response($response);
    exit;
}

$response['data'] = getJsonObjFromResult($result);
$result->free();

$conn->close();

deliver_JSONresponse($response);
exit;
?>
