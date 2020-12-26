<?php
header("Access-Control-Allow-Origin: *");

$curl_handler = curl_init();

curl_setopt($curl_handler, CURLOPT_URL, "https://fantasy.premierleague.com/api/bootstrap-static/");
curl_setopt($curl_handler, CURLOPT_HEADER, 0);
curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handler, CURLOPT_FAILONERROR, true);

$data = curl_exec($curl_handler);
$data = json_decode($data, true);

$status_code = curl_getinfo($curl_handler, CURLINFO_HTTP_CODE);

curl_close($curl_handler);

$response = array();
$teams = array();
$players = array();

$response['status'] = $status_code;

if ($status_code === 200) {
    foreach ($data['teams'] as $key => $item) {
        $team = array();
        $team['id'] = $item['id'];
        $team['name'] = $item['name'];

        $teams[] = $team;
    }
    $response['teams'] = $teams;

    foreach ($data['elements'] as $key => $item) {
        $player = array();
        $player['id'] = $item['id'];
        $player['first_name'] = $item['first_name'];
        $player['second_name'] = $item['second_name'];
        $player['web_name'] = $item['web_name'];
        $player['team'] = $item['team'];
        $player['team_name'] = $teams[$player['team'] - 1]['name'];
        $player['display_name'] = $player['web_name'] . ' - ' . $player['team_name'];

        $players[] = $player;
    }
    $response['players'] = $players;
} else {
    $response['error'] = 'Error getting player data';
}

echo json_encode($response);
