<?php
$request_body = file_get_contents('php://input');
$request_data = json_decode($request_body, true);

check_required_fields(array('conversation_id'));
$conversation_id = $request_data['conversation_id'];


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("DELETE FROM conversations WHERE id = ?");

$stmt->bind_param("i", $conversation_id);

if (!$stmt->execute()) {
    echo "Error: " . $stmt->error;
    exit;
}

if ($stmt->affected_rows > 0) {
    $response['code'] = 1;
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $response['data'] = "Conversation deleted successfully";
} else {
    $response['code'] = 5;
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $response['data'] = "Conversation not found";
}

deliver_response($response);
mysqli_stmt_close($stmt);
mysqli_close($conn);

?>