<?php
header('Access-Control-Allow-Origin: *');

require 'includes/db-connect.php';

$response = array();
$users = array();

$query = "SELECT user_id, user_firstname, user_lastname, user_email, user_role, user_deleted FROM `tbl_users` WHERE user_deleted = 0";

$results = mysqli_query($conn, $query) or die(mysqli_error($conn));
$count = mysqli_num_rows($results);

if ($count > 0) {
    while ($result = mysqli_fetch_object($results)) {
        $user = array();
        $user['user_id'] = $result->user_id;
        $user['user_firstname'] = $result->user_firstname;
        $user['user_lastname'] = $result->user_lastname;
        $user['user_email'] = $result->user_email;
        $user['user_role'] = $result->user_role;
        $user['user_deleted'] = $result->user_deleted;
        
        array_push($users, $user);
    }

    $response['status'] = 'success';
    $response['users'] = $users;
} else {
    $response['status'] = 'info';
    $response['message'] = 'No users found';
}

echo json_encode($response);
