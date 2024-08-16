<?php
header("Access-Control-Allow-Origin: *");

require 'includes/db-connect.php';

$current_date_time = date("Y-m-d H:i:s");
$current_time = strtotime($current_date_time);

$response = array();

if (isset($_POST['firstname']) and isset($_POST['lastname']) and isset($_POST['email']) and isset($_POST['admin'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $admin = $_POST['admin'];

    $query = "SELECT user_firstname, user_lastname, user_email FROM `tbl_users` WHERE user_firstname='$firstname' and user_lastname='$lastname' and user_email='$email'";

    $results = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $count = mysqli_num_rows($results);

    if ($count > 0) {
        $response['status'] = 'error';
        $response['error'] = 'User already registered';
    } else {
        // Set password (uses the first part of the user's email address, before the @ symbol)
        $email_substrings = explode("@", $email);
        $email_substring_password = $email_substrings[0];
        $password = md5($email_substring_password);

        // Add picks
        $insert_query = "INSERT INTO `tbl_users` (user_firstname, user_lastname, user_email, user_password, user_role, user_deleted) VALUES ('$firstname', '$lastname', '$email', '$password', '$admin', '0')";

        $insert_results = mysqli_query($conn, $insert_query) or die(mysqli_error($conn));

        if ($insert_results) {
            $response['status'] = 'success';
            $response['message'] = 'User added';
            $response['password'] = $email_substring_password;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error adding user';
        }
    }

    echo json_encode($response);
} else {
    $response['status'] = 'error';
    $response['error'] = 'There was a problem with adding the user.';

    echo json_encode($response);
}
