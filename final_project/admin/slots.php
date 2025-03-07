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

    // Extract booked slots from the response
    $booked_slots = array_column($booked_slots_data['booked_slots'], 'slot');

    // Compare and remove booked slots from available slots
    $available_slots = [];
    foreach ($slots_data['slots'] as $index => $slot) {
        if (!in_array($slot, $booked_slots)) {
            $available_slots[] = [
                'time' => $slot,
                'filter' => $slots_data['filter'][$index]
            ];
        }
    }

    return [
        'name' => $slots_data['name'],
        'available_slots' => $available_slots,
        'booked_slots' => $booked_slots_data['booked_slots']
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
$booked_slots = $slots_data['booked_slots'];

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/view_slots.css">
</head>
<body>
<?php include "navbar.php" ?>
    <h1><?php echo strtoupper($name); ?> Slots</h1>
    <div class="date-picker">
    <?php
    $dates = [
        [
            'label' => '<small>' . date("M") . '</small><br><span class="date-large">' . date("j") . '</span><br><small>' . date("Y") . '</small>', // Today
            'date' => date("Y-m-d")
        ],
        [
            'label' => '<small>' . date("M", strtotime("+1 day")) . '</small><br><span class="date-large">' . date("j", strtotime("+1 day")) . '</span><br><small>' . date("Y", strtotime("+1 day")) . '</small>', // Tomorrow
            'date' => date("Y-m-d", strtotime("+1 day"))
        ],
        [
            'label' => '<small>' . date("M", strtotime("+2 days")) . '</small><br><span class="date-large">' . date("j", strtotime("+2 days")) . '</span><br><small>' . date("Y", strtotime("+2 days")) . '</small>', // Day After
            'date' => date("Y-m-d", strtotime("+2 days"))
        ]
    ];

    foreach ($dates as $date) {
        $is_active = ($date['date'] === $selected_date) ? 'active' : '';
        echo '
        <a href="slots.php?game_id=' . $game_id . '&date=' . $date['date'] . '&filter=' . $selected_filter . '">
            <button class="' . $is_active . '">' . $date['label'] . '</button>
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
                <div class="slot-card available" data-slot="' . $slot['time'] . '" data-filter="' . $slot['filter'] . '">
                    <p>' . $slot['time'] . '</p>
                </div>';
            }
        } else {
            echo "<p>No slots available.</p>";
        }
        ?>
    </div>

    <!-- Booked Slots Details Section -->
    <div class="booked-details">
        <h2>Booked Slots Details</h2>
        <table class="booked-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Slot</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($booked_slots)) {
                    foreach ($booked_slots as $booking) {
                        echo '
                        <tr>
                            <td>' . htmlspecialchars($booking['username']) . '</td>
                            <td>' . htmlspecialchars($booking['phone_no']) . '</td>
                            <td>' . htmlspecialchars($booking['email']) . '</td>
                            <td>' . htmlspecialchars($booking['slot']) . '</td>
                        </tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">No slots booked for this date.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Popup for Booking -->
    <div id="overlay"></div>
    <div id="popup">
        <h3>Book Slot</h3>
        <input type="text" id="phoneNumber" placeholder="Enter Phone Number" maxlength="10">
        <span id="phone_status"></span>
        <button id="submitBooking" disabled>Submit</button>
        <button id="closePopup">Close</button>
    </div>

    <script>
        // JavaScript for handling the popup and API submission
        const overlay = document.getElementById('overlay');
        const popup = document.getElementById('popup');
        const phoneInput = document.getElementById('phoneNumber');
        const submitButton = document.getElementById('submitBooking');
        const closePopupButton = document.getElementById('closePopup');
        let selectedSlot = null;

        // Show popup when a slot is clicked
        document.querySelectorAll('.slot-card.available').forEach(slot => {
            slot.addEventListener('click', () => {
                selectedSlot = slot.getAttribute('data-slot');
                popup.style.display = 'block';
                overlay.style.display = 'block';
            });
        });

        // Close popup
        closePopupButton.addEventListener('click', () => {
            popup.style.display = 'none';
            overlay.style.display = 'none';
        });

        // Validate phone number in real-time
        phoneInput.addEventListener('input', () => {
            const phone = phoneInput.value;
            if (phone.length === 10 && /^\d+$/.test(phone)) {
                submitButton.disabled = false;
            } else {
                submitButton.disabled = true;
            }
        });

        <!-- jQuery & AJAX -->
$(document).ready(function(){
    $("#phone_number").on("keyup", function(){
        let phone = $(this).val().trim();
        
        if (phone.length === 10 && /^\d{10}$/.test(phone)) { 
            // Send AJAX request to check in database
            $.ajax({
                url: "check_phone.php", // Backend script
                type: "POST",
                data: { phone: phone },
                success: function(response) {
                    if (response === "exists") {
                        $("#phone_status").text("✔ Phone number is valid")
                                          .css("color", "green");
                    } else {
                        $("#phone_status").text("✖ Phone number is not registered")
                                          .css("color", "red");
                    }
                }
            });
        } else {
            $("#phone_status").text("Phone number must be 10 digits")
                              .css("color", "red");
        }
    });
});

        // Submit booking to API
        submitButton.addEventListener('click', async () => {
            const phone = phoneInput.value;
            const gameId = <?php echo $game_id; ?>;
            const date = "<?php echo $selected_date; ?>";
            const slot = selectedSlot;

            const data = {
                game_id: gameId,
                date: date,
                slot: slot,
                phone_no: phone
            };

            try {
                const response = await fetch('http://192.168.0.130/final_project/final_project/Api\'s/book_game_admin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    alert('Slot booked successfully!');
                    window.location.reload(); // Refresh the page
                } else {
                    alert('Failed to book slot: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while booking the slot.');
            }
        });
    </script>
</body>
</html>