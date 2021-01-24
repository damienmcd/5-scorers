<?php
header('Access-Control-Allow-Origin: *');

require 'includes/db-connect.php';

if (isset($_POST['game_id']) and isset($_POST['user_id'])) {
    $game_id = $_POST['game_id'];
    $user_id = $_POST['user_id'];

    $query = "SELECT picks_id, picks_player_1, picks_player_2, picks_player_3, picks_player_4, picks_player_5 FROM `tbl_player_picks` WHERE picks_game_id = '$game_id' AND picks_user_id = '$user_id'";

    $results = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $count = mysqli_num_rows($results);

    $response = array();

    while ($result = mysqli_fetch_object($results)) {
        $picks_id = $result->picks_id;
        $picks_player_1 = $result->picks_player_1;
        $picks_player_2 = $result->picks_player_2;
        $picks_player_3 = $result->picks_player_3;
        $picks_player_4 = $result->picks_player_4;
        $picks_player_5 = $result->picks_player_5;
    }

    if ($count > 0) {
        $picks = array();
        $response['status'] = 'success';
        $picks['id'] = $game_id;
        $picks['player_1'] = (int)$picks_player_1;
        $picks['player_2'] = (int)$picks_player_2;
        $picks['player_3'] = (int)$picks_player_3;
        $picks['player_4'] = (int)$picks_player_4;
        $picks['player_5'] = (int)$picks_player_5;

        $response['picks'] = $picks;
    } else {
        $response['status'] = 'info';
        $response['message'] = 'No picks found';
    }

    echo json_encode($response);
}
