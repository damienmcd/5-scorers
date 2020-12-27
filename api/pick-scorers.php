<?php
header("Access-Control-Allow-Origin: *");

require 'includes/db-connect.php';

if (isset($_POST['game_id']) and isset($_POST['user_id']) and isset($_POST['scorer1']) and isset($_POST['scorer2']) and isset($_POST['scorer3']) and isset($_POST['scorer4']) and isset($_POST['scorer5'])) {
    $game_id = $_POST['game_id'];
    $user_id = $_POST['user_id'];
    $scorer1 = $_POST['scorer1'];
    $scorer2 = $_POST['scorer2'];
    $scorer3 = $_POST['scorer3'];
    $scorer4 = $_POST['scorer4'];
    $scorer5 = $_POST['scorer5'];

    $query = "SELECT picks_user_id, picks_game_id FROM `tbl_player_picks` WHERE picks_user_id='$user_id' and picks_game_id='$game_id'";

    $results = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $count = mysqli_num_rows($results);

    $response = array();

    if ($count == 1) {
        // Edit picks
        $update_query = "UPDATE `tbl_player_picks` SET picks_player_1 = '$scorer1', picks_player_2 = '$scorer2', picks_player_3 = '$scorer3', picks_player_4 = '$scorer4', picks_player_5 = '$scorer5' WHERE picks_user_id='$user_id' and picks_game_id='$game_id'";

        $update_results = mysqli_query($conn, $update_query) or die(mysqli_error($conn));

        var_dump($update_results);

        if ($update_results) {
            $response['status'] = 'success';
            $response['message'] = 'Picks updated';
        } else {
            $response['status'] = 'error';
            $response['error'] = 'Error updating scorers';
        }
    } else {
        // Add picks
        $insert_query = "INSERT INTO `tbl_player_picks` (picks_game_id, picks_user_id, picks_player_1, picks_player_2, picks_player_3, picks_player_4, picks_player_5) VALUES ('$game_id', '$user_id', '$scorer1', '$scorer2', '$scorer3', '$scorer4', '$scorer5')";

        $insert_results = mysqli_query($conn, $insert_query) or die(mysqli_error($conn));

        var_dump($insert_results);

        if ($insert_results) {
            $response['status'] = 'success';
            $response['message'] = 'Picks added';
        } else {
            $response['status'] = 'error';
            $response['error'] = 'Error adding scorers';
        }
    }

    echo json_encode($response);
}
