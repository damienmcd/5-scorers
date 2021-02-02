<?php
header('Access-Control-Allow-Origin: *');

require 'includes/db-connect.php';

if (isset($_POST['game_id']) and isset($_POST['user_role']) and isset($_POST['user_id']) and isset($_POST['deadline_passed'])) {
    $response = array();
    $players_picks = array();

    $game_id = $_POST['game_id'];
    $user_role = $_POST['user_role'];
    $user_id = $_POST['user_id'];
    $deadline_passed = $_POST['deadline_passed'];

    if ($user_role == 'admin' or $deadline_passed == 'true') {
        // $response['query_type'] = 'admin or deadline passed';
        $query = "SELECT tbl_player_picks.picks_id AS picks_id, tbl_player_picks.picks_user_id AS picks_user_id, tbl_users.user_firstname AS user_firstname, tbl_users.user_lastname AS user_lastname, tbl_player_picks.picks_player_1 AS picks_player_1, tbl_player_picks.picks_player_2 AS picks_player_2, tbl_player_picks.picks_player_3 AS picks_player_3, tbl_player_picks.picks_player_4 AS picks_player_4, tbl_player_picks.picks_player_5 AS picks_player_5
        FROM tbl_player_picks
        LEFT JOIN tbl_users
        ON tbl_player_picks.picks_user_id = tbl_users.user_id
        WHERE tbl_player_picks.picks_game_id = '$game_id'";
    } else {
        // $response['query_type'] = 'NOT admin and deadline NOT passed';
        $query = "SELECT tbl_player_picks.picks_id AS picks_id, tbl_player_picks.picks_user_id AS picks_user_id, tbl_users.user_firstname AS user_firstname, tbl_users.user_lastname AS user_lastname, tbl_player_picks.picks_player_1 AS picks_player_1, tbl_player_picks.picks_player_2 AS picks_player_2, tbl_player_picks.picks_player_3 AS picks_player_3, tbl_player_picks.picks_player_4 AS picks_player_4, tbl_player_picks.picks_player_5 AS picks_player_5
        FROM tbl_player_picks
        LEFT JOIN tbl_users
        ON tbl_player_picks.picks_user_id = tbl_users.user_id
        WHERE tbl_player_picks.picks_game_id = '$game_id'
        AND tbl_player_picks.picks_user_id = '$user_id'";
    }

    $results = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $count = mysqli_num_rows($results);

    // $response['game_id'] = $game_id;
    // $response['user_role'] = $user_role;
    // $response['user_id'] = $user_id;
    // $response['deadline_passed'] = $deadline_passed;

    if ($count > 0) {
        while ($result = mysqli_fetch_object($results)) {
            $player_picks = array();
            $player_picks['picks_id'] = $result->picks_id;
            $player_picks['picks_user_id'] = $result->picks_user_id;
            $player_picks['user_firstname'] = $result->user_firstname;
            $player_picks['user_lastname'] = $result->user_lastname;
            $player_picks['player_1'] = $result->picks_player_1;
            $player_picks['player_2'] = $result->picks_player_2;
            $player_picks['player_3'] = $result->picks_player_3;
            $player_picks['player_4'] = $result->picks_player_4;
            $player_picks['player_5'] = $result->picks_player_5;

            array_push($players_picks, $player_picks);
        }
        $response['players_picks'] = $players_picks;
        $response['status'] = 'success';
    } else {
        $response['status'] = 'info';
        $response['message'] = 'No picks found';
    }

    echo json_encode($response);
}
