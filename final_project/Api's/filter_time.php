<?php
// Set headers for JSON output and CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Function to convert 12-hour AM/PM format to 24-hour format
function convertTo24HourFormat($timeStr)
{
    // Remove extra spaces if there are any
    $timeStr = trim($timeStr);

    // Convert time from 12-hour format (with AM/PM) to 24-hour format
    $dateTime = DateTime::createFromFormat('h:iA', $timeStr);
    
    // If successful, return the time in 24-hour format (HH:mm)
    return $dateTime ? $dateTime->format('H:i') : false;
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

        // Set the timezone to Indian Standard Time (IST)
        $timezone = new DateTimeZone('Asia/Kolkata');

        // Get the current time in IST
        $current_time = new DateTime('now', $timezone); // Get the current time in IST
        $current_time_24hr = $current_time->format('H:i'); // Get current time in 24-hour format (HH:mm)

        // Initialize an array for filtered slots
        $filtered_slots = [];
        $filtered_filter_values = [];

        // Check if the requested date is today
        if ($requested_date_str == $current_time->format('Y-m-d')) {
            foreach ($slots as $index => $slot) {
                // Extract start time from the slot (e.g., "1:00PM-2:00PM")
                list($start_time_str, $end_time_str) = explode('-', $slot);
                if ($end_time_str == "12:00PM") {
                    $amOrPm = "AM"; // Skip this slot if start or end time is missing
                }
                else{

                    $amOrPm = strtoupper(substr($slot, -2)); 
                }
                // Convert start time to 24-hour format using IST
                $start_time_24hr = convertTo24HourFormat($start_time_str . $amOrPm);
                
                
                // Compare only the start time to the current time
                if ($start_time_24hr) {
                    // Only show slots where the current time is less than the start time
                    if ($current_time_24hr < $start_time_24hr) {
                        $filtered_slots[] = $slot;
                        $filtered_filter_values[] = $filter_values[$index]; // Match filter value with the slot
                    }
                }
            }
        } elseif ($requested_date_obj > $current_time) {
            // Future date: Show all slots
            $filtered_slots = $slots;
            $filtered_filter_values = $filter_values;  // No filtering needed for future dates
        } else {
            // Past date: No slots available
            $filtered_slots = [];
            $filtered_filter_values = [];
        }

        // Prepare response
        $response = [
            "id" => $row["id"],
            "name" => $row["name"],
            "slots" => $filtered_slots, // Filtered slots based on start time
            "filter" => $filtered_filter_values  // Filter values as is
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
?>
