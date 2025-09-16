<?php
// --- "Get" alle likes  

$postvars = $_GET; 
// https://www.w3schools.com/php/func_var_isset.asp#:~:text=This%20function%20returns%20true%20if,with%20the%20unset()%20function.

if (isset($postvars['like_id'])) {
   $sql = "select l.like_id, l.account_id, l.post_id, l.reply_id, l.liked_at, coalesce(p.account_id, r.account_id) as like_account from likes l left join posts p on l.post_id = p.post_id left join replies r on l.reply_id = r.reply_id where l.like_id = ?";   
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        $response['code'] = 7;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $response['data'] = $conn->error;
        deliver_response($response);
        exit;
    }

    $stmt->bind_param("i", $postvars['like_id']);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "select like_id, account_id, post_id, liked_at, reply_id from likes";
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