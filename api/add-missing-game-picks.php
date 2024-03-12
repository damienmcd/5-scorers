<?php
header('Access-Control-Allow-Origin: *');

require 'includes/db-connect.php';

// function arrayHasUserPicks($picks_array, $user_id) {
//     $user_ids = array();
//     foreach ($picks_array as $key => $user_picks) {
//         array_push($user_ids, $user_id['picks_user_id']);
//     }
//     return $user_ids;
// }

// if (isset($_POST['game_id']) and isset($_POST['user_role']) and isset($_POST['deadline_passed'])) {
if (isset($_POST['game_id']) and isset($_POST['user_role'])) {
    $response = array();
    $all_user_ids = array();
    $users_with_picks_ids = array();
    $users_without_picks_picks = array();
    $user_picks_to_add = array();
    $user_picks_to_add_this_game_id = array();
    // $players_picks = array();

    $game_id = $_POST['game_id'];
    $user_role = $_POST['user_role'];
    // $deadline_passed = $_POST['deadline_passed'];

    /**
     * 1. Get all active user IDs (SELECT user_id WHERE user_deleted = 0)
     * 2. Get all current game picks user IDs (SELECT picks_user_id FROM tbl_player_picks WHERE picks_game_id = $_POST['game_id'])
     * 3. Compare all users array with current game picks user ids and save diff
     * 4. Get all picks for users without picks
     * 5. Filter out most recent picks from all picks array
     * 6. Insert picks for user from most recent week into database for current game_id
     * 7. Send response to frontend
     */

    /**
     * 1. Get all active user IDs (SELECT user_id WHERE user_deleted = 0)
     */
    $all_users_query = "SELECT tbl_users.user_id AS user_id
    FROM tbl_users
    WHERE tbl_users.user_deleted = 0";

    $all_users = mysqli_query($conn, $all_users_query) or die(mysqli_error($conn));
    $all_users_count = mysqli_num_rows($all_users);

    if ($all_users_count > 0) {
        while ($user = mysqli_fetch_object($all_users)) {
            array_push($all_user_ids, $user->user_id);
        }
        $response['all_users'] = $all_user_ids;
        // $response['status'] = 'success';
    } else {
        $response['status'] = 'info';
        $response['message'] = 'No users found';
    }

    /**
     * 2. Get all current game picks user IDs
     */
    $users_with_picks_query = "SELECT tbl_player_picks.picks_user_id AS picks_user_id
    FROM tbl_player_picks
    WHERE tbl_player_picks.picks_game_id = $game_id
    ORDER BY tbl_player_picks.picks_user_id ASC";

    $users_with_picks = mysqli_query($conn, $users_with_picks_query) or die(mysqli_error($conn));
    $users_with_picks_count = mysqli_num_rows($users_with_picks);

    if ($users_with_picks_count > 0) {
        while ($user = mysqli_fetch_object($users_with_picks)) {
            array_push($users_with_picks_ids, $user->picks_user_id);
        }
        $response['users_with_picks'] = $users_with_picks_ids;
        // $response['status'] = 'success';
    } else {
        $response['status'] = 'info';
        $response['message'] = 'No users with picks found';
    }

    /**
     * 3. Compare all users array with current game picks user ids and save diff
     */
    $users_without_picks_ids = array_diff($all_user_ids, $users_with_picks_ids);
        $response['users_without_picks'] = $users_without_picks_ids;
    # reinstantiate $array1 variable 
    $users_without_picks_ids_no_keys = array_values($users_without_picks_ids);
    // $str_users_without_picks_ids = implode(",", $users_without_picks_ids_no_keys);
    
    /**
     * 4. Get all picks for users without picks
     */
    for ($i = 0; $i < count($users_without_picks_ids_no_keys); $i++) {
        $user_without_picks_id = $users_without_picks_ids_no_keys[$i];
        $users_without_picks_picks_query = "SELECT tbl_player_picks.*
        FROM tbl_player_picks
        WHERE tbl_player_picks.picks_user_id = $user_without_picks_id
        ORDER BY tbl_player_picks.picks_user_id ASC, tbl_player_picks.picks_game_id DESC
        LIMIT 1";

        $user_without_picks_picks = mysqli_query($conn, $users_without_picks_picks_query) or die(mysqli_error($conn));
        $user_without_picks_picks_count = mysqli_num_rows($user_without_picks_picks);

        $response['user_without_picks_picks_count'] = $user_without_picks_picks_count;

        if ($user_without_picks_picks_count > 0) {
            while ($user_missing_picks = mysqli_fetch_object($user_without_picks_picks)) {
                $user_missing_picks_this_game_id['picks_game_id'] = $game_id;
                $user_missing_picks_this_game_id['picks_player_1'] = $user_missing_picks->picks_player_1;
                $user_missing_picks_this_game_id['picks_player_2'] = $user_missing_picks->picks_player_2;
                $user_missing_picks_this_game_id['picks_player_3'] = $user_missing_picks->picks_player_3;
                $user_missing_picks_this_game_id['picks_player_4'] = $user_missing_picks->picks_player_4;
                $user_missing_picks_this_game_id['picks_player_5'] = $user_missing_picks->picks_player_5;
                $user_missing_picks_this_game_id['picks_user_id'] = $user_missing_picks->picks_user_id;

                array_push($user_picks_to_add, $user_missing_picks);
                array_push($user_picks_to_add_this_game_id, $user_missing_picks_this_game_id);

                // Add picks
                $insert_query = "INSERT INTO `tbl_player_picks` (picks_game_id, picks_user_id, picks_player_1, picks_player_2, picks_player_3, picks_player_4, picks_player_5) 
                VALUES (
                    '$game_id', 
                    '$user_missing_picks->picks_user_id', 
                    '$user_missing_picks->picks_player_1', 
                    '$user_missing_picks->picks_player_2', 
                    '$user_missing_picks->picks_player_3', 
                    '$user_missing_picks->picks_player_4', 
                    '$user_missing_picks->picks_player_5'
                )";

                $insert_results = mysqli_query($conn, $insert_query) or die(mysqli_error($conn));

                if ($insert_results) {
                    $response['status'] = 'success';
                    $response['message'] = 'Missing picks added';
                    $response['picks_added_message'] = 'Missing picks added';
                    $response['count_picks_added'] = $i+1;
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Error adding missing picks';
                    $response['picks_added_message'] = 'Error adding missing picks';
                }
            }
        }
        $response['user_picks_to_add'] = $user_picks_to_add;
        $response['user_picks_to_add_this_game_id'] = $user_picks_to_add_this_game_id;
    }
    // if (count($user_picks_to_add) > 0) {
    //     $response['user_picks_to_add'] = $user_picks_to_add;
    // } else {
        // $response['status'] = 'info';
        // $response['message'] = 'No user picks to add';
    // }


    echo json_encode($response);
}
