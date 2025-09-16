<?php
// --- "Get" alle posts  

$postvars = $_GET; 


if (isset($postvars['post_id'])) {
    $sql = "select 
    posts.post_id, 
    posts.content, 
    posts.account_id, 
    posts.created_at, 
    accounts.username, 
    accounts.profilephoto, 
    posts.post_image,
    count(likes.post_id) AS like_count, 
    (
        select count(*) 
        from replies 
        where replies.post_id = posts.post_id
    ) as reply_count
   from posts
   join accounts on posts.account_id = accounts.account_id
   left join likes on posts.post_id = likes.post_id
   where posts.post_id = ?
   group by posts.post_id, posts.content, posts.created_at, accounts.username, accounts.profilephoto
   order by posts.created_at desc;";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        $response['code'] = 7;
        $response['status'] = $api_response_code[$response['code']]['HTTP Response'];
        $response['data'] = $conn->error;
        deliver_response($response);
        exit;
    }

    $stmt->bind_param("i", $postvars['post_id']);
    $stmt->execute();
    $result = $stmt->get_result();
   }else{
      $sql="select posts.post_id, posts.content, posts.account_id, posts.created_at, accounts.username, accounts.profilephoto, posts.post_image, count(likes.post_id) as like_count, 
    (select count(*) 
    from replies 
    where replies.post_id = posts.post_id) as reply_count
    from posts
    join accounts on posts.account_id = accounts.account_id
    left join likes on posts.post_id = likes.post_id
    group by posts.post_id, posts.content, posts.created_at, accounts.username, accounts.profilephoto
    order by posts.created_at desc";
    $result = $conn->query($sql);
   }


// geen prepared statement nodig, aangezien we geen parameters
// van de gebruiker verwerken.


if (!$result) {
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
//deliver_response($response);

exit;
?>