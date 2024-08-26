<?php
header("Access-Control-Allow-Origin: *");

require 'includes/db-connect.php';

$current_date_time = date("Y-m-d H:i:s");
$current_time = strtotime($current_date_time);

$response = array();

if (isset($_POST['user_id']) and isset($_POST['user_email'])) {
    $user_id = $_POST['user_id'];
    $user_email = $_POST['user_email'];

    $email_substrings = explode("@", $user_email);
    $email_substring_password = $email_substrings[0];
    $password = md5($email_substring_password);

    $query = "UPDATE `tbl_users` SET `user_password` = '$password' WHERE `tbl_users`.`user_id` = $user_id";

    $update_results = mysqli_query($conn, $query) or die(mysqli_error($conn));

    if ($update_results) {
        $response['status'] = 'success';
        $response['message'] = 'User\s password updated';
        $response['update_results'] = $update_results;
    } else {
        $response['status'] = 'error';
        $response['error'] = 'Error updating password';
    }

    echo json_encode($response);
} else {
    $response['status'] = 'error';
    $response['error'] = 'There was a problem with updating the password.';

    echo json_encode($response);
}
