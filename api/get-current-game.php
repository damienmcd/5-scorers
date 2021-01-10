<?php
header('Access-Control-Allow-Origin: *');

require 'includes/db-connect.php';

$query = "SELECT game_id, game_week_no, game_deadline, game_jackpot, game_winner_ids FROM `tbl_game_weeks` ORDER BY game_id DESC LIMIT 1";

$results = mysqli_query($conn, $query) or die(mysqli_error($conn));
$count = mysqli_num_rows($results);

while ($result = mysqli_fetch_object($results)) {
    $game_id = $result->game_id;
    $game_week_no = $result->game_week_no;
    $game_deadline = $result->game_deadline;
    $game_jackpot = $result->game_jackpot;
    $game_winner_ids = $result->game_winner_ids;
}

$response = array();

if ($count > 0) {
    $game = array();
    $response['status'] = 'success';
    $game['id'] = $game_id;
    $game['week_no'] = $game_week_no;
    $game['deadline'] = $game_deadline;
    $game['jackpot'] = $game_jackpot;
    $game['winner_ids'] = $game_winner_ids;

    $response['game'] = $game;
} else {
    $response['status'] = 'error';
    $response['message'] = 'No game found';
}

echo json_encode($response);
