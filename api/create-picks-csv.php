<?php
header('Access-Control-Allow-Origin: *');

$response = array();

if (isset($_POST['game_week_no']) && isset($_POST['picks'])) {

    $week_no = $_POST['game_week_no'];
    $response['week_no'] = $week_no;
    $inputted_picks = json_decode($_POST['picks']);
    $response['picks'] = $inputted_picks;

    // Format the dates to the desired formats
    $today = date("d/m/Y");
    $week_no_date_formatted = date('Ymd', strtotime($today));

    // declare the array for the week picks
    // $week_picks = array();
    $week_picks = array(
        array('user_firstname' => 'Damien', 'user_lastname' => 'McDonnell', 'player_1' => 'Watkins - Aston Villa', 'player_2' => 'Grealish - Aston Villa', 'player_3' => 'Rashford - Man Utd', 'player_4' => 'Sterling - Man City', 'player_5' => 'Salah - Liverpool'),
        array('user_firstname' => 'Niall', 'user_lastname' => 'McDermott', 'player_1' => 'Kane - Spurs', 'player_2' => 'Kane - Spurs', 'player_3' => 'Kane - Spurs', 'player_4' => 'Grealish - Aston Villa', 'player_5' => 'Jesus - Man City')
    );

    $week_picks_formatted = array();
    $count_users = 0;
    // Call to get the picks for the week_no
    // getPicksByWeekNo();

    // Loop through the line items and get the required details
    foreach ($inputted_picks as $key => $week_pick) {
        // Add the line item to the array
        $user = $week_pick[0];
        $week_pick_content = array($user, $week_pick[1], $week_pick[2], $week_pick[3], $week_pick[4], $week_pick[5]);

        $week_picks_formatted[] = $week_pick_content;

        $count_users++;
    }


    // Header row in the CSV
    $csv = '"User","Scorer 1","Scorer 2","Scorer 3","Scorer 4","Scorer 5"' . "\r\n";

    // Add each line item
    foreach ($week_picks_formatted as $week_picks_formatted_record) {
        $csv .= '"' . $week_picks_formatted_record[0] . '","' . $week_picks_formatted_record[1] . '","' . $week_picks_formatted_record[2] . '","' . $week_picks_formatted_record[3] . '","' . $week_picks_formatted_record[4] . '","' . $week_picks_formatted_record[5] . '"' . "\r\n";
    }

    // Create the CSV file and write it to the designated location
    $csv_handler = fopen('../csv-exports/csv-export-' . $week_no . $week_no_date_formatted . '.csv', 'w');
    fwrite($csv_handler, $csv);
    fclose($csv_handler);

    $response['file_url'] = '/csv-exports/csv-export-' . $week_no . $week_no_date_formatted . '.csv';

    $response['status'] = 'success';
    $response['result'] = 'CSV Created';
} else {
    $response['error'] = 'Error creating CSV export';
}

echo json_encode($response);
