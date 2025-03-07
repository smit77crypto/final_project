<?php
// Set the appropriate headers for JSON output and CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");  // Explicitly set the response type to JSON

// Get the raw POST data (for GET request with JSON body)
$data = json_decode(file_get_contents("php://input"), true);

// Check if the "id" parameter is provided in the request
if (isset($data['id']) && isset($data['date'])) {
    $game_id = $data['id'];
    $requested_date = $data['date'];  // Get the date requested for checking the slots

    // Database connection details
    include 'db_connect.php';

    // Query to fetch game data based on the provided id
    $sql = "SELECT * FROM games WHERE id = $game_id and deleteval=1";
    $result = $conn->query($sql);

    // Initialize an array to store the response data
    $response = [];

    if ($result->num_rows > 0) {
        // Fetch the game data
        $row = $result->fetch_assoc();
        
        // Split slots and filter values into arrays
        $slots = explode(",", $row["slots"]);
        $filter_values = explode(",", $row["filter_value"]);

        // Get current time and today's date
        $now = new DateTime();
        $today_str = $now->format('Y-m-d');  // Today's date in 'Y-m-d' format

        $requested_date_obj = new DateTime($requested_date);
        $requested_date_str = $requested_date_obj->format('Y-m-d');  // Format the requested date

        // Initialize an array for filtered slots
        $filtered_slots = [];

        // Check if the requested date is today or tomorrow
        if ($requested_date_str == $today_str) {
            // It's today, filter out past slots
            foreach ($slots as $slot) {
                // Extract start and end time from the slot (example: "10:00-11:00AM")
                $time_parts = explode("-", $slot);
                $start_time_str = $time_parts[0];  // Start time (e.g., "10:00AM")
                $end_time_str = $time_parts[1];    // End time (e.g., "11:00AM")

                // Create DateTime objects for start time and end time
                $start_time = DateTime::createFromFormat('h:iA', $start_time_str);
                $end_time = DateTime::createFromFormat('h:iA', $end_time_str);

                // If the current time is before the end time, add it to the filtered slots
                if ($now < $end_time) {
                    $filtered_slots[] = $slot;
                }
            }
        } else if ($requested_date_obj > $now) {
            // It's tomorrow or a future date, so show all slots
            $filtered_slots = $slots;
        } else {
            // It's a past date, so no slots should be shown
            $filtered_slots = [];
        }

        // Prepare the response
        $response = [
            "id" => $row["id"],
            "name" => $row["name"],
            "slots" => $filtered_slots,  // Only the filtered slots
            "filter" => $filter_values   // Filter values as is
        ];
    } else {
        // If no game found with the given id
        $response = ["error" => "Game not found"];
    }

    // Close the connection
    $conn->close();

    // Return the game data as JSON
    echo json_encode($response);
} else {
    // If id or date is not provided in the request
    echo json_encode(["error" => "No id or date provided"]);
}
?>
