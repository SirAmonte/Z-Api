<?php
$postvars = $_GET; 
// https://www.w3schools.com/php/func_var_isset.asp#:~:text=This%20function%20returns%20true%20if,with%20the%20unset()%20function.

if (isset($postvars['account_id'])) {
    $sql = "select account_id, username, email, password, profilephoto, bio, regdate, created_at, is_verified from accounts WHERE account_id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        $response['code'] = 7;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $response['data'] = $conn->error;
        deliver_response($response);
        exit;
    }

    $stmt->bind_param("i", $postvars['account_id']);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "select account_id, username, email, password, profilephoto, bio, regdate, created_at, is_verified from accounts";
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
