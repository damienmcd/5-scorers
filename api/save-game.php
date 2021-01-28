<?php
header('Access-Control-Allow-Origin: *');

require 'includes/db-connect.php';

$response = array();

if (isset($_POST['save_mode']) and isset($_POST['game_id']) and isset($_POST['week_no']) and isset($_POST['deadline_date']) and isset($_POST['jackpot'])) {
    $save_mode = $_POST['save_mode'];
    $game_id = $_POST['game_id'];
    $week_no = $_POST['week_no'];
    $deadline_date = $_POST['deadline_date'];
    $jackpot = $_POST['jackpot'];

    $response['deadline_date'] = $deadline_date;
    $response['save_mode'] = $save_mode;

    // $post_data = array();
    // $post_data['save_mode'] = $save_mode;
    // $post_data['game_id'] = $game_id;
    // $post_data['week_no'] = $week_no;
    // $post_data['deadline_date'] = $deadline_date;
    // $post_data['jackpot'] = $jackpot;
    // $response['post_data'] = $post_data;

    if ($save_mode == 'update') {
        // $query = "SELECT picks_user_id, picks_game_id FROM `tbl_player_picks` WHERE picks_user_id='$user_id' and picks_game_id='$game_id'";
        $query = "SELECT game_id, game_week_no, game_deadline, game_jackpot FROM `tbl_game_weeks` WHERE game_id='$game_id' ORDER BY game_id DESC LIMIT 1";

        $results = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $count = mysqli_num_rows($results);

        if ($count == 1) {
            // Edit picks
            $update_query = "UPDATE `tbl_game_weeks` SET game_week_no = '$week_no', game_deadline = '$deadline_date', game_jackpot = '$jackpot' WHERE game_id='$game_id'";

            $update_results = mysqli_query($conn, $update_query) or die(mysqli_error($conn));

            if ($update_results) {
                $response['status'] = 'success';
                $response['message'] = 'Game updated.';
            } else {
                $response['status'] = 'error';
                $response['error'] = 'Error updating game.';
            }
        } else {
            $response['status'] = 'error';
            $response['error'] = 'No game found.';
        }

    } elseif ($save_mode == 'create') {
        $create_query = "INSERT INTO `tbl_game_weeks` (`game_week_no`, `game_deadline`, `game_jackpot`) VALUES ('$week_no', '$deadline_date', '$jackpot')";

        $create_results = mysqli_query($conn, $create_query) or die(mysqli_error($conn));

        if ($create_results) {
            $response['status'] = 'success';
            $response['message'] = 'New game created.';
        } else {
            $response['status'] = 'error';
            $response['error'] = 'Error creating new game';
        }
    }
} else {
    $response['status'] = 'error';
    $response['error'] = 'Error saving game.';
}

echo json_encode($response);
