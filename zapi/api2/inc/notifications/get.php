<?php
// --- "Get" alle notis  

$postvars = $_GET; 
// https://www.w3schools.com/php/func_var_isset.asp#:~:text=This%20function%20returns%20true%20if,with%20the%20unset()%20function.

if (isset($postvars['account_id'])) {
    $sql = "select notification_id, type, created_at, context, is_read from notifications WHERE account_id = ?";
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
    $sql = "select notification_id, account_id, type, created_at, context, is_read from notifications";
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