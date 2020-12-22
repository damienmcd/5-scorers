<?php
require 'includes/db-connect.php';

if (isset($_POST['email']) and isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = "SELECT user_id, user_firstname, user_lastname, user_email, user_password, user_role FROM `tbl_users` WHERE user_email='$email' and user_password='$password' and user_deleted = 0";

    $results = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $count = mysqli_num_rows($results);

    while ($result = mysqli_fetch_object($results)) {
        $user_id = $result->user_id;
        $user_firstname = $result->user_firstname;
        $user_lastname = $result->user_lastname;
        $user_email = $result->user_email;
        $user_role = $result->user_role;
    }

    $response = array();

    if ($count == 1) {
        $response['id'] = $user_id;
        $response['firstname'] = $user_firstname;
        $response['lastname'] = $user_lastname;
        $response['email'] = $user_email;
        $response['role'] = $user_role;
        $response['loggedIn'] = true;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid login';
    }

    echo json_encode($response);
}
