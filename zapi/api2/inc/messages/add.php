<?php
if (!defined('INDEX')) {
    http_response_code(403);
    exit('Access forbidden');
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['conversation_id'], $data['sender_id'], $data['message'])) {
    http_response_code(400);
    exit('Bad request: missing required fields');
}

$conversation_id = $data['conversation_id'];
$sender_id = $data['sender_id'];
$message = $data['message'];

$sql = "INSERT INTO messages (conversation_id, sender_id, message) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('iis', $conversation_id, $sender_id, $message);

if ($stmt->execute()) {
    $message_id = $conn->insert_id;
    echo json_encode(['success' => true, 'message_id' => $message_id]);
    exit;
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send message']);
    echo "Error: " . $stmt->error;
    exit;
}
?>