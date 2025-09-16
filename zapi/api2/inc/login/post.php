<?php
// LOGIN
// --- antwoord met de gebruikergegevens indien de combo bestaat

// Zijn de nodige parameters meegegeven in de request?
check_required_fields(["username","password"]);

// de login nakijken
// let op : we halen deze uit $postvars ipv uit $_POST, wat je online meer zal tegenkomen.
$stmt = $conn->prepare("select account_id, username FROM accounts where username like ? and password like ?"); 

if (!$stmt){
	//oeps, probleem met prepared statement!
	$response['code'] = 7;
	$response['status'] = 200; // 'ok' status, anders een bad request ...
	$response['data'] = $conn->error;
	deliver_response($response);
}

// bind parameters ( s = string | i = integer | d = double | b = blob )
if(!$stmt->bind_param("ss", $postvars['username'], $postvars['password'])){
	// binden van de parameters is mislukt
	$response['code'] = 7;
	$response['status'] = 200; // 'ok' status, anders een bad request ...
	$response['data'] = $conn->error;
	deliver_response($response);
}

if (!$stmt->execute()) {
	// het uitvoeren van het statement zelf mislukte
	$response['code'] = 7;
	$response['status'] = $api_response_code[$response['code']]['HTTP Response'];
	$response['data'] = $conn->error;
	deliver_response($response);
}

$result = $stmt->get_result();

if (!$result) {
	// er kon geen resultset worden opgehaald
	$response['code'] = 7;
	$response['status'] = $api_response_code[$response['code']]['HTTP Response'];
	$response['data'] = $conn->error;
	deliver_response($response);
}

// Vorm de resultset om naar een structuur die we makkelijk kunnen 
// doorgeven en stop deze in $response['data']
$response['data'] = getJsonObjFromResult($result); // -> fetch_all(MYSQLI_ASSOC)
// maak geheugen vrij op de server door de resultset te verwijderen
$result->free();
// sluit de connectie met de databank
$conn->close();
// Return Response to browser
deliver_JSONresponse($response);
?>