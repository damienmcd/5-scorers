<?php
header('Access-Control-Allow-Origin: *');

require 'includes/db-connect.php';

$response = array();

if (isset($_POST['user_id']) and isset($_POST['new_password'])) {
    $user_id = $_POST['user_id'];
    $new_password = md5($_POST['new_password']);

    // $post_data = array();
    // $post_data['save_mode'] = $save_mode;
    // $post_data['game_id'] = $game_id;
    // $post_data['week_no'] = $week_no;
    // $post_data['deadline_date'] = $deadline_date;
    // $post_data['jackpot'] = $jackpot;
    // $response['post_data'] = $post_data;


    // $query = "SELECT picks_user_id, picks_game_id FROM `tbl_player_picks` WHERE picks_user_id='$user_id' and picks_game_id='$game_id'";
    $query = "SELECT user_firstname, user_lastname FROM `tbl_users` WHERE user_id='$user_id' ORDER BY user_id DESC LIMIT 1";

    $results = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $count = mysqli_num_rows($results);

    if ($count == 1) {
        // Edit picks
        $update_query = "UPDATE `tbl_users` SET user_password = '$new_password' WHERE user_id='$user_id'";

        $update_results = mysqli_query($conn, $update_query) or die(mysqli_error($conn));

        if ($update_results) {
            $response['status'] = 'success';
            $response['message'] = 'Password updated.';
        } else {
            $response['status'] = 'error';
            $response['error'] = 'Error updating password.';
        }
    } else {
        $response['status'] = 'error';
        $response['error'] = 'No user found.';
    }


} else {
    $response['status'] = 'error';
    $response['error'] = 'Error saving new password.';
}

echo json_encode($response);
