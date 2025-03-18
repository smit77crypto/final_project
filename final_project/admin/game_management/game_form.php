<?php
$gameId = isset($_GET['id']) ? $_GET['id'] : '';
$game_name = $half_hour = $hour = $slots = '';
if ($gameId) {
    include('../connect_database.php');
    $sql = "SELECT * FROM games WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gameId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Pre-fill the form with existing data
        $game_name = $user['name'];
        $half_hour = $user['half_hour'];
        $hour = $user['hour'];
        $slots = $user['slots'];
    }
}
$selectedSlots = !empty($slots) ? explode(',', $slots) : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/game_form.css">
</head>

<body>
    <?php include '../outside_navbar.php' ?>

    <div class="container">
        <div class="form-container" id="gameForm">
            <div>
                <h2 id="formTitle"><?php echo $gameId ? 'Update Game' : 'Add Game'; ?></h2>
            </div>

            <div class="mid">
                <div class="left1">
                    <h2 id="formTitle" style="visibility:hidden">Game Detail</h2>

                    <input type="hidden" id="gameId" name="gameId" value="<?php echo $gameId ; ?>">

                    <div class="form-group">
                        <label>Game Name <span><?php echo $gameId ? '' : '*'; ?></span></label>
                        <input type="text" id="gameName" name="gameName" value="<?php echo $game_name; ?>">
                        <span class="error-message" id="gameNameError"></span>
                    </div>
                    <div class="form-group">
                        <label>Price(₹) 30 mins <span><?php echo $gameId ? '' : '*'; ?></span></label></label>
                        <input type="text" id="price30" name="price30" value="<?php echo $half_hour; ?>">
                        <span class="error-message" id="price30Error"></span>
                    </div>
                    <div class="form-group">
                        <label>Price(₹) 1 hour <span><?php echo $gameId ? '' : '*'; ?></span></label></label>
                        <input type="text" id="price60" name="price60" value="<?php echo $hour; ?>">
                        <span class="error-message" id="price60Error"></span>
                    </div>
                    <div class="form-group">
                        <label>Upload Image (Game Section)</label>
                        <input type="file" id="gameImage" name="gameImage">
                        <span class="error-message" id="gameImageError"></span>
                    </div>
                    <div class="form-group">
                        <label>Upload Image (Main Page Slider)</label>
                        <input type="file" id="sliderImage" name="sliderImage">
                        <span class="error-message" id="sliderImageError"></span>
                    </div>
                </div>
                <div class="right1">
                    <!-- Slot Filter Buttons -->
                    <div class="slot-filter">
                        <button onclick="filterSlots('30min')" class="active">30 mins</button>
                        <button onclick="filterSlots('1hr')" class="active">1 hour</button>
                        <button onclick="filterSlots('both')" class="active">All</button>
                    </div>

                    <!-- Time Slots Sections -->
                    <div class="time-slot-section">
                        <div class="slot-header" onclick="toggleSlots('morning-slots')">
                            <h3>Morning Slot (10 AM - 12 PM)</h3>
                        </div>
                        <div class="slot-options" id="morning-slots" style="display: none;">
                            <label class="slot-item">
                                <input type="checkbox" value="10:00-10:30AM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('10:00-10:30AM', $selectedSlots) ? 'checked' : ''; ?>>
                                10:00-10:30AM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="10:30-11:00AM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('10:30-11:00AM', $selectedSlots) ? 'checked' : ''; ?>>
                                10:30-11:00AM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="11:00-11:30AM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('11:00-11:30AM', $selectedSlots) ? 'checked' : ''; ?>>
                                11:00-11:30AM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="11:30-12:00PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('11:30-12:00PM', $selectedSlots) ? 'checked' : ''; ?>>
                                11:30-12:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="10:00-11:00AM" data-type="1hr"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('10:00-11:00AM', $selectedSlots) ? 'checked' : ''; ?>>
                                10:00-11:00AM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="11:00-12:00PM" data-type="1hr"
                                    <?php echo in_array('11:00-12:00PM', $selectedSlots) ? 'checked' : ''; ?>
                                    onchange="updateSelectedSlots()">
                                11:00-12:00PM
                            </label>
                        </div>
                    </div>

                    <div class="time-slot-section">
                        <div class="slot-header" onclick="toggleSlots('afternoon-slots')">
                            <h3>Afternoon Slot (12 PM - 4 PM)</h3>
                        </div>
                        <div class="slot-options" id="afternoon-slots" style="display: none;">
                            <label class="slot-item">
                                <input type="checkbox" value="12:00-12:30PM" data-type="30min"
                                    <?php echo in_array('12:00-12:30PM', $selectedSlots) ? 'checked' : ''; ?>
                                    onchange="updateSelectedSlots()">
                                12:00-12:30PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="12:30-1:00PM" data-type="30min"
                                    <?php echo in_array('12:30-1:00PM', $selectedSlots) ? 'checked' : ''; ?>
                                    onchange="updateSelectedSlots()">
                                12:30-1:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="1:00-1:30PM" data-type="30min"
                                    <?php echo in_array('1:00-1:30PM', $selectedSlots) ? 'checked' : ''; ?>
                                    onchange="updateSelectedSlots()">
                                1:00-1:30PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="1:30-2:00PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('1:30-2:00PM', $selectedSlots) ? 'checked' : ''; ?>>
                                1:30-2:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="2:00-2:30PM" data-type="30min"
                                    <?php echo in_array('2:00-2:30PM', $selectedSlots) ? 'checked' : ''; ?>
                                    onchange="updateSelectedSlots()">
                                2:00-2:30PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="2:30-3:00PM" data-type="30min"
                                    <?php echo in_array('2:30-3:00PM', $selectedSlots) ? 'checked' : ''; ?>
                                    onchange="updateSelectedSlots()">
                                2:30-3:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="3:00-3:30PM" data-type="30min"
                                    <?php echo in_array('3:00-3:30PM', $selectedSlots) ? 'checked' : ''; ?>
                                    onchange="updateSelectedSlots()">
                                3:00-3:30PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="3:30-4:00PM" data-type="30min"
                                    <?php echo in_array('3:30-4:00PM', $selectedSlots) ? 'checked' : ''; ?>
                                    onchange="updateSelectedSlots()">
                                3:30-4:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="12:00-1:00PM" data-type="1hr"
                                    <?php echo in_array('12:00-1:00PM', $selectedSlots) ? 'checked' : ''; ?>
                                    onchange="updateSelectedSlots()">
                                12:00-1:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="1:00-2:00PM" data-type="1hr"
                                    <?php echo in_array('1:00-2:00PM', $selectedSlots) ? 'checked' : ''; ?>
                                    onchange="updateSelectedSlots()">
                                1:00-2:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="2:00-3:00PM" data-type="1hr"
                                    <?php echo in_array('2:00-3:00PM', $selectedSlots) ? 'checked' : ''; ?>
                                    onchange="updateSelectedSlots()">
                                2:00-3:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="3:00-4:00PM" data-type="1hr"
                                    <?php echo in_array('3:00-4:00PM', $selectedSlots) ? 'checked' : ''; ?>
                                    onchange="updateSelectedSlots()">
                                3:00-4:00PM
                            </label>
                        </div>
                    </div>

                    <div class="time-slot-section">
                        <div class="slot-header" onclick="toggleSlots('evening-slots')">
                            <h3>Evening Slot (4 PM - 8 PM)</h3>
                        </div>
                        <div class="slot-options" id="evening-slots" style="display: none;">
                            <label class="slot-item">
                                <input type="checkbox" value="4:00-4:30PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('4:00-4:30PM', $selectedSlots) ? 'checked' : ''; ?>>

                                4:00-4:30PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="4:30-5:00PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('4:30-5:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                4:30-5:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="5:00-5:30PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('5:00-5:30PM', $selectedSlots) ? 'checked' : ''; ?>>

                                5:00-5:30PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="5:30-6:00PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('5:30-6:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                5:30-6:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="6:00-6:30PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('6:00-6:30PM', $selectedSlots) ? 'checked' : ''; ?>>

                                6:00-6:30PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="6:30-7:00PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('6:30-7:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                6:30-7:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="7:00-7:30PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('7:00-7:30PM', $selectedSlots) ? 'checked' : ''; ?>>

                                7:00-7:30PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="7:30-8:00PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('7:30-8:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                7:30-8:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="4:00-5:00PM" data-type="1hr"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('4:00-5:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                4:00-5:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="5:00-6:00PM" data-type="1hr"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('5:00-6:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                5:00-6:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="6:00-7:00PM" data-type="1hr"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('6:00-7:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                6:00-7:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="7:00-8:00PM" data-type="1hr"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('7:00-8:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                7:00-8:00PM
                            </label>
                        </div>
                    </div>

                    <div class="time-slot-section">
                        <div class="slot-header" onclick="toggleSlots('night-slots')">
                            <h3>Night Slot (8 PM - 11:30 PM)</h3>
                        </div>
                        <div class="slot-options" id="night-slots" style="display: none;">
                            <label class="slot-item">
                                <input type="checkbox" value="8:00-8:30PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('8:00-8:30PM', $selectedSlots) ? 'checked' : ''; ?>>

                                8:00-8:30PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="8:30-9:00PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('8:30-9:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                8:30-9:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="9:00-9:30PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('9:00-9:30PM', $selectedSlots) ? 'checked' : ''; ?>>

                                9:00-9:30PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="9:30-10:00PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('9:30-10:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                9:30-10:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="10:00-10:30PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('10:00-10:30PM', $selectedSlots) ? 'checked' : ''; ?>>

                                10:00-10:30PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="10:30-11:00PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('10:30-11:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                10:30-11:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="11:00-11:30PM" data-type="30min"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('11:00-11:30PM', $selectedSlots) ? 'checked' : ''; ?>>

                                11:00-11:30PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="8:00-9:00PM" data-type="1hr"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('8:00-9:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                8:00-9:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="9:00-10:00PM" data-type="1hr"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('9:00-10:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                9:00-10:00PM
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="10:00-11:00PM" data-type="1hr"
                                    onchange="updateSelectedSlots()"
                                    <?php echo in_array('10:00-11:00PM', $selectedSlots) ? 'checked' : ''; ?>>

                                10:00-11:00PM
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selected Slots -->
            <div class="selected-slots" id="selected-slots">
                <h3>Selected Slots</h3>
                <div id="selected-list"></div>
                <span class="error-message" id="slotError"></span>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button class="btn btn-add" type="submit"><?php echo $gameId ? 'Update' : 'Add'; ?></button>
                <button type="button" class="btn btn-cancel"
                    onclick="window.location.href='../game_management.php'">Cancel</button>
            </div>
        </div>
    </div>
    <script src="../js/game_form.js"></script>
    <script>
        function toggleSlots(slotId) {
            const slotSection = document.getElementById(slotId);
            if (slotSection.style.display === "none") {
                slotSection.style.display = "block";
            } else {
                slotSection.style.display = "none";
            }
        }
    </script>
</body>

</html>