<?php
header('Access-Control-Allow-Origin: *');

$response = array();

if (isset($_POST['game_week_no'])) {
    $scorers = array();

    $game_week_no = $_POST['game_week_no'];
    $game_week_url = 'https://fantasy.premierleague.com/api/fixtures/?event=' . $game_week_no;

    $curl_handler = curl_init();

    curl_setopt($curl_handler, CURLOPT_URL, $game_week_url);
    curl_setopt($curl_handler, CURLOPT_HEADER, 0);
    curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handler, CURLOPT_FAILONERROR, true);
    curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, 0);

    $data = curl_exec($curl_handler);
    $data = json_decode($data, true);

    $status_code = curl_getinfo($curl_handler, CURLINFO_HTTP_CODE);

    curl_close($curl_handler);

    $response['status'] = $status_code;

    if ($status_code === 200) {
        $response['status'] = 'success';
        $response['game_week_data'] = $data;
    } else {
        $response['error'] = 'Error getting player data';
    }
} else {
    $response['error'] = 'Error getting player data';
}

echo json_encode($response);
