<?php
header('Access-Control-Allow-Origin: *');

require 'includes/db-connect.php';

$response = array();

if (isset($_POST['save_mode']) and isset($_POST['game_id']) and isset($_POST['week_no']) and isset($_POST['deadline_date']) and isset($_POST['jackpot'])) {
    $save_mode = $_POST['save_mode'];
    $game_id = (int)$_POST['game_id'];
    $week_no = (int)$_POST['week_no'];
    $deadline_date = $_POST['deadline_date'];
    $jackpot = (int)$_POST['jackpot'];

    $response['deadline_date'] = $deadline_date;
    $response['save_mode'] = $save_mode;

    if ($save_mode == 'update') {
        $query = "SELECT game_id, game_week_no, game_deadline, game_jackpot FROM `tbl_game_weeks` WHERE `game_id` = '$game_id' ORDER BY game_id DESC LIMIT 1";

        $results = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $count = mysqli_num_rows($results);

        while ($result = mysqli_fetch_object($results)) {
            $game_id = $result->game_id;
        }
        if ($count > 0) {
            $update_query = "UPDATE `tbl_game_weeks` SET `game_week_no` = $week_no AND `game_deadline` = '$deadline_date' AND `game_jackpot` = '$jackpot' WHERE `tbl_game_weeks`.`game_id` = $game_id";

            $update_results = mysqli_query($conn, $update_query) or die(mysqli_error($conn));

            if ($update_results) {
                $game = array();
                $game['id'] = $game_id;
                $game['week_no'] = $week_no;
                $game['deadline'] = $deadline_date;
                $game['jackpot'] = $jackpot;

                $response['status'] = 'success';
                $response['game'] = $game;
                $response['message'] = 'Game updated.';
            } else {
                $response['status'] = 'error';
                $response['error'] = 'No game found';
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
