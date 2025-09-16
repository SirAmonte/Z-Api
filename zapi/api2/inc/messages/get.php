<?php
if (!defined('INDEX')) {
    http_response_code(403);
    exit('Access forbidden');
}

if (!isset($_GET['conversation_id'])) {
    echo json_encode(['error' => 'Conversation ID is required']);
    exit;
}

$conversation_id = $_GET['conversation_id'];

$sql = "SELECT * FROM messages 
        WHERE conversation_id = ?
        ORDER BY created_at ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $conversation_id);
$stmt->execute();

$messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode($messages);
?>