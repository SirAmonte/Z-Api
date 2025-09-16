<?php

$user_id = $_GET['user_id'];

$stmt = $conn->prepare("SELECT id, user_id_1, user_id_2, last_message, last_updated FROM conversations WHERE user_id_1 = ? OR user_id_2 = ? ORDER BY last_updated DESC");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();

$conversations = [];
while ($row = $result->fetch_assoc()) {
    $conversations[] = $row;
}

echo '{"code":1,"status":200,"data":' . json_encode($conversations) . '}';

$stmt->close();
$conn->close();

?>