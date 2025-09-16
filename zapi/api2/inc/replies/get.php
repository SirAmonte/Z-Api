<?php
// --- "Get" alle replies  

$postvars = $_GET; 
// https://www.w3schools.com/php/func_var_isset.asp#:~:text=This%20function%20returns%20true%20if,with%20the%20unset()%20function.

if (isset($postvars['like_id'])) {
    $sql = "select r.reply_id, r.account_id as reply_account_id, r.post_id, l.like_id, p.account_id as like_account,
     r.content, r.created_at, r.parent_reply_id, a.username, a.profilephoto, count(l.reply_id) as like_count, 
     (select count(*) from replies as sub_replies where sub_replies.parent_reply_id = r.reply_id) as reply_count
      from replies r join accounts a on r.account_id = a.account_id join posts p on r.post_id = p.post_id left join
      likes l on r.reply_id = l.reply_id where l.like_id = ? group by r.reply_id, r.account_id, r.post_id, p.account_id, r.content, r.created_at, r.parent_reply_id, a.username, a.profilephoto;";
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
    $sql = "select replies.reply_id, replies.account_id, replies.post_id, replies.content, replies.created_at, replies.parent_reply_id, 
      accounts.username, accounts.profilephoto, count(likes.reply_id) as like_count, 
      (select count(*) 
      from replies as sub_replies
      where sub_replies.parent_reply_id = replies.reply_id) as reply_count
      from replies
      join accounts on replies.account_id = accounts.account_id
      left join likes on replies.reply_id = likes.reply_id
      group by replies.reply_id, replies.account_id, replies.post_id, replies.content, replies.created_at, replies.parent_reply_id, accounts.username, accounts.profilephoto";
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