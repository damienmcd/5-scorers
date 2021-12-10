<?php
header("Access-Control-Allow-Origin: *");

$curl_handler = curl_init();

curl_setopt($curl_handler, CURLOPT_URL, "https://fantasy.premierleague.com/api/bootstrap-static/");
curl_setopt($curl_handler, CURLOPT_HEADER, 0);
curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handler, CURLOPT_FAILONERROR, true);
curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, 0);

$curl_data = curl_exec($curl_handler);
$data = json_decode($curl_data, true);

$status_code = curl_getinfo($curl_handler, CURLINFO_HTTP_CODE);

curl_close($curl_handler);

$response = array();
$teams = array();
$players = array();

$response['status'] = $status_code;
$response['curl_error'] = curl_error($curl_handler);

if ($status_code === 200) {
    $response['success'] = 'Got player data';
    foreach ($data['teams'] as $key => $item) {
        $team = array();
        $team['id'] = $item['id'];
        $team['name'] = $item['name'];

        $teams[] = $team;
    }
    $response['teams'] = $teams;

    foreach ($data['elements'] as $key => $item) {
        $player = array();
        $player['value'] = $item['id'];
        $web_name = $item['web_name'];
        $team = $item['team'];
        $team_name = $teams[$team - 1]['name'];
        $player['text'] = $web_name . ' - ' . $team_name;
        $player['team_id'] = $teams[$team - 1]['id'];

        $players[] = $player;
    }
    $response['players'] = $players;
} else {
    $response['error'] = 'Error getting player data';
}

echo json_encode($response);
