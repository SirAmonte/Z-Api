<?php

check_required_fields(array('user_id_1', 'user_id_2'));

$user_id_1 = $postvars['user_id_1'];
$user_id_2 = $postvars['user_id_2'];

$stmt = $conn->prepare("SELECT id FROM conversations WHERE (user_id_1, user_id_2) IN ((?, ?), (?, ?))");
$stmt->bind_param("iiii", $user_id_1, $user_id_2, $user_id_2, $user_id_1);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    $response['code'] = 5;
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $response['data'] = "Conversation already exists";
    deliver_response($response);
} else {
    $stmt = $conn->prepare("INSERT INTO conversations (user_id_1, user_id_2) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id_1, $user_id_2);
    $stmt->execute();

    $response['code'] = 1;
    $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
    $response['data'] = array('conversation_id' => $stmt->insert_id);
    deliver_response($response);
}