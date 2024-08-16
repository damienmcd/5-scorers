<?php
header("Access-Control-Allow-Origin: *");

require 'includes/db-connect.php';

$current_date_time = date("Y-m-d H:i:s");
$current_time = strtotime($current_date_time);

$response = array();

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $query = "UPDATE `tbl_users` SET `user_deleted` = '1' WHERE `tbl_users`.`user_id` = $user_id";

    $update_results = mysqli_query($conn, $query) or die(mysqli_error($conn));

    if ($update_results) {
        $response['status'] = 'success';
        $response['message'] = 'User deleted';
        $response['update_results'] = $update_results;
    } else {
        $response['status'] = 'error';
        $response['error'] = 'Error updating correct picks';
    }

    $results = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $count = mysqli_num_rows($results);

    echo json_encode($response);
} else {
    $response['status'] = 'error';
    $response['error'] = 'There was a problem with deleting the user.';

    echo json_encode($response);
}
