<?php
// Set headers for JSON output and CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Function to sort slots by start time
function sortSlotsByTime($slots)
{
    usort($slots, function ($a, $b) {
        // Extract start times (e.g., "10:00" from "10:00-10:30AM")
        preg_match('/(\d{1,2}:\d{2}[APM]+)/', $a, $startA);
        preg_match('/(\d{1,2}:\d{2}[APM]+)/', $b, $startB);

        if (!isset($startA[1]) || !isset($startB[1])) {
            return 0; // If no valid start time, keep the original order
        }

        // Convert to DateTime objects for comparison
        $timeA = DateTime::createFromFormat('h:iA', $startA[1]);
        $timeB = DateTime::createFromFormat('h:iA', $startB[1]);

        return $timeA <=> $timeB; // Ascending order
    });

    return $slots;
}

// Get the raw POST data (for GET request with JSON body)
$data = json_decode(file_get_contents("php://input"), true);

// Check if "id" and "date" parameters are provided in the request
if (isset($data['id']) && isset($data['date'])) {
    $game_id = $data['id'];
    $requested_date = $data['date']; // Example: "2025/03/09"

    // Convert the requested date from "YYYY/MM/DD" to "Y-m-d" format
    $requested_date_obj = DateTime::createFromFormat('Y/m/d', $requested_date);
    if (!$requested_date_obj) {
        echo json_encode(["error" => "Invalid date format. Use YYYY/MM/DD"]);
        exit;
    }
    $requested_date_str = $requested_date_obj->format('Y-m-d'); // Format as "Y-m-d"

    // Database connection details
    include 'db_connect.php';

    // Query to fetch game data based on the provided id
    $sql = "SELECT * FROM games WHERE id = $game_id AND deleteval = 1";
    $result = $conn->query($sql);

    // Initialize response array
    $response = [];

    if ($result->num_rows > 0) {
        // Fetch game data
        $row = $result->fetch_assoc();

        // Split slots and filter values into arrays
        $slots = explode(",", $row["slots"]);
        $filter_values = explode(",", $row["filter_value"]);

        // Sort slots before filtering
        $slots = sortSlotsByTime($slots);

        // Get current date and time
        $now = new DateTime();
        $today_str = $now->format('Y-m-d');

        // Initialize an array for filtered slots
        $filtered_slots = [];

        // Check if the requested date is today
        if ($requested_date_str == $today_str) {
            foreach ($slots as $slot) {
                // Extract start and end time from the slot (example: "10:00-10:30AM")
                $time_parts = explode("-", $slot);
                if (count($time_parts) != 2) continue; // Skip invalid slot formats

                $start_time_str = trim($time_parts[0]); // Example: "10:00"
                $end_time_str = trim($time_parts[1]);   // Example: "10:30AM"

                // Create DateTime objects for start and end times with AM/PM included
                $start_time = DateTime::createFromFormat('h:iA', $start_time_str . substr($end_time_str, -2)); // Extract AM/PM from end time
                $end_time = DateTime::createFromFormat('h:iA', $end_time_str);

                if ($start_time && $end_time) {
                    $start_time->setDate($now->format('Y'), $now->format('m'), $now->format('d'));
                    $end_time->setDate($now->format('Y'), $now->format('m'), $now->format('d'));

                    // Compare with current time
                    if ($now < $end_time) {
                        $filtered_slots[] = $slot;
                    }
                }
            }
        } elseif ($requested_date_obj > $now) {
            // Future date: Show all slots
            $filtered_slots = $slots;
        } else {
            // Past date: No slots available
            $filtered_slots = [];
        }

        // Prepare response
        $response = [
            "id" => $row["id"],
            "name" => $row["name"],
            "slots" => $filtered_slots, // Filtered slots
            "filter" => $filter_values  // Filter values as is
        ];
    } else {
        $response = ["error" => "Game not found"];
    }

    // Close database connection
    $conn->close();

    // Return response as JSON
    echo json_encode($response);
} else {
    echo json_encode(["error" => "No id or date provided"]);
}
