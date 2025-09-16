<?php
// --- "Get" the servertime 

$sql="SELECT now() as servertime";

// geen prepared statement nodig, aangezien we geen parameters
// van de gebruiker verwerken.

$result = $conn -> query($sql);

if (!$result) {
	$response['code'] = 7;
	$response['status'] = $api_response_code[$response['code']]['HTTP Response'];
	$response['data'] = $conn->error;
	deliver_response($response);
}

$rows = array();

while ($row = $result -> fetch_assoc()) {
	$rows[] = $row;
}

$response['data'] = $rows[0];

mysqli_free_result($result);
$conn->close();
// Return Response to browser
deliver_response($response);
?>