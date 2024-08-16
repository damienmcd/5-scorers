<?php
header("Access-Control-Allow-Origin: *");

require 'includes/db-connect.php';

$response = array();

if (isset($_POST['picks_id']) and isset($_POST['picks_user_id']) and isset($_POST['correct_picks'])) {
    $picks_id = $_POST['picks_id'];
    $picks_user_id = $_POST['picks_user_id'];
    $correct_picks = $_POST['correct_picks'];

    $query = "SELECT picks_id, picks_user_id, correct_picks FROM `tbl_player_picks` WHERE picks_id='$picks_id' and picks_user_id='$picks_user_id'";

    $results = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $count = mysqli_num_rows($results);

    if ($count == 1) {
        // Edit picks
        $update_query = "UPDATE `tbl_player_picks` SET correct_picks = '$correct_picks' WHERE picks_id=$picks_id and picks_user_id=$user_id";

        $update_results = mysqli_query($conn, $update_query) or die(mysqli_error($conn));

        if ($update_results) {
            $response['status'] = 'success';
            $response['message'] = 'Correct Picks ('.$picks_id.') updated to ' . $correct_picks . ' for user ' . $picks_user_id;
            $response['update_results'] = $update_results;
        } else {
            $response['status'] = 'error';
            $response['error'] = 'Error updating correct picks';
        }
    } else {
            $response['status'] = 'error';
            $response['message'] = 'No picks found for user';
    }

    echo json_encode($response);
} else {
    $response['status'] = 'error';
    $response['error'] = 'There was a problem with updating correct picks for user ' . $user_id;

    echo json_encode($response);
}
