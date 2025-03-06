<?php
if (!isset($_GET['game_id'])) {
    die("Game ID is missing.");
}

$game_id = $_GET['game_id'];

// Function to fetch slot data from the API
function fetchSlotData($game_id, $date) {
    // Fetch all slots from the first API (slots_data.php)
    $slots_api_url = "http://192.168.0.130/final_project/final_project/Api's/slots_data.php";
    $slots_json_data = json_encode(['id' => $game_id]);

    $slots_options = [
        'http' => [
            'header' => "Content-type: application/json\r\n",
            'method' => 'POST',
            'content' => $slots_json_data
        ]
    ];
    $slots_context = stream_context_create($slots_options);
    $slots_response = file_get_contents($slots_api_url, false, $slots_context);

    if ($slots_response === FALSE) {
        return ["error" => "Error fetching slots data."];
    }

    $slots_data = json_decode($slots_response, true);

    // Fetch booked slots from the second API (book_slots.php)
    $booked_slots_api_url = "http://192.168.0.130/final_project/final_project/Api's/book_slots.php";
    $booked_json_data = json_encode(['game_id' => $game_id, 'date' => $date]);

    $booked_options = [
        'http' => [
            'header' => "Content-type: application/json\r\n",
            'method' => 'POST',
            'content' => $booked_json_data
        ]
    ];
    $booked_context = stream_context_create($booked_options);
    $booked_response = file_get_contents($booked_slots_api_url, false, $booked_context);

    if ($booked_response === FALSE) {
        return ["error" => "Error fetching booked slots data."];
    }

    $booked_slots_data = json_decode($booked_response, true);

    // Compare and remove booked slots from available slots
    $available_slots = [];
    foreach ($slots_data['slots'] as $index => $slot) {
        if (!in_array($slot, $booked_slots_data['booked_slots'])) {
            $available_slots[] = [
                'time' => $slot,
                'filter' => $slots_data['filter'][$index]
            ];
        }
    }

    return [
        'name' => $slots_data['name'],
        'available_slots' => $available_slots
    ];
}

// Get the selected date (default is today)
$selected_date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

// Fetch slot data for the selected date
$slots_data = fetchSlotData($game_id, $selected_date);

if (isset($slots_data['error'])) {
    die($slots_data['error']);
}

$name = $slots_data['name'];
$available_slots = $slots_data['available_slots'];

// Get the selected filter (default is All)
$selected_filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

// Filter slots based on the selected filter
$filtered_slots = array_filter($available_slots, function ($slot) use ($selected_filter) {
    return $selected_filter === 'All' || $slot['filter'] === $selected_filter;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Slots</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            font-size: 2rem;
            color: #333;
        }
        .date-picker {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .date-picker button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #ddd;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .date-picker button.active {
            background-color: #ff4444;
            color: #fff;
        }
        .filter-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .filter-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #ddd;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .filter-buttons button.active {
            background-color: #ff4444;
            color: #fff;
        }
        .slots-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }
        .slot-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .slot-card.available {
            background-color: #4CAF50;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1><?php echo strtoupper($name); ?> Slots</h1>
    <div class="date-picker">
        <?php
        $dates = [
            'Today' => date("Y-m-d"),
            'Tomorrow' => date("Y-m-d", strtotime("+1 day")),
            'Day After' => date("Y-m-d", strtotime("+2 days"))
        ];

        foreach ($dates as $label => $date) {
            $is_active = ($date === $selected_date) ? 'active' : '';
            echo '
            <a href="slots.php?game_id=' . $game_id . '&date=' . $date . '&filter=' . $selected_filter . '">
                <button class="' . $is_active . '">' . $label . '</button>
            </a>';
        }
        ?>
    </div>
    <div class="filter-buttons">
        <?php
        $filters = ['All', '30min', '1hr'];
        foreach ($filters as $filter) {
            $is_active = ($filter === $selected_filter) ? 'active' : '';
            echo '
            <a href="slots.php?game_id=' . $game_id . '&date=' . $selected_date . '&filter=' . $filter . '">
                <button class="' . $is_active . '">' . $filter . '</button>
            </a>';
        }
        ?>
    </div>
    <div class="slots-container">
        <?php
        if (!empty($filtered_slots)) {
            foreach ($filtered_slots as $slot) {
                echo '
                <div class="slot-card available">
                    <p>' . $slot['time'] . '</p>
                    <p>' . $slot['filter'] . '</p>
                </div>';
            }
        } else {
            echo "<p>No slots available.</p>";
        }
        ?>
    </div>
</body>
</html>