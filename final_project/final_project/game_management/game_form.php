<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/game_form.css">
</head>

<body>
    <div class="container">
        <div class="form-container" id="gameForm">
            <div>
                <h2 id="formTitle">Add Game</h2>
            </div>

            <div class="mid">
                <div class="left">
                    <h2 id="formTitle">Game Detail</h2>
                    <div class="form-group">
                        <label>Game Name</label>
                        <input type="text" id="gameName" name="gameName">
                        <span class="error-message" id="gameNameError"></span>
                    </div>
                    <div class="form-group">
                        <label>Price for 30 mins</label>
                        <input type="text" id="price30" name="price30">
                        <span class="error-message" id="price30Error"></span>
                    </div>
                    <div class="form-group">
                        <label>Price for 1 hour</label>
                        <input type="text" id="price60" name="price60">
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
                <div class="right">
                    <!-- Slot Filter Buttons -->
                    <div class="slot-filter">
                        <button onclick="filterSlots('30min')" class="active">30 mins</button>
                        <button onclick="filterSlots('1hr')" class="active">1 hour</button>
                        <button onclick="filterSlots('both')" class="active">Both</button>
                    </div>

                    <!-- Time Slots Sections -->
                    <div class="time-slot-section">
                        <div class="slot-header">
                            <h3>Morning Slot (10 AM - 12 PM)</h3>
                        </div>
                        <div class="slot-options" id="morning-slots">
                            <label class="slot-item">
                                <input type="checkbox" value="10:00-10:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                10:00-10:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="10:30-11:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                10:30-11:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="11:00-11:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                11:00-11:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="11:30-12:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                11:30-12:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="10:00-11:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                10:00-11:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="11:00-12:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                11:00-12:00
                            </label>
                        </div>
                    </div>

                    <div class="time-slot-section">
                        <div class="slot-header">
                            <h3>Afternoon Slot (12 PM - 4 PM)</h3>
                        </div>
                        <div class="slot-options" id="afternoon-slots">
                            <label class="slot-item">
                                <input type="checkbox" value="12:00-12:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                12:00-12:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="12:30-1:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                12:30-1:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="1:00-1:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                1:00-1:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="1:30-2:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                1:30-2:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="2:00-2:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                2:00-2:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="2:30-3:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                2:30-3:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="3:00-3:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                3:00-3:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="3:30-4:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                3:30-4:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="12:00-1:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                12:00-1:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="1:00-2:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                1:00-2:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="2:00-3:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                2:00-3:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="3:00-4:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                3:00-4:00
                            </label>
                        </div>
                    </div>

                    <div class="time-slot-section">
                        <div class="slot-header">
                            <h3>Evening Slot (4 PM - 8 PM)</h3>
                        </div>
                        <div class="slot-options" id="evening-slots">
                            <label class="slot-item">
                                <input type="checkbox" value="4:00-4:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                4:00-4:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="4:30-5:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                4:30-5:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="5:00-5:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                5:00-5:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="5:30-6:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                5:30-6:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="6:00-6:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                6:00-6:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="6:30-7:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                6:30-7:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="7:00-7:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                7:00-7:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="7:30-8:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                7:30-8:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="4:00-5:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                4:00-5:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="5:00-6:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                5:00-6:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="6:00-7:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                6:00-7:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="7:00-8:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                7:00-8:00
                            </label>
                        </div>
                    </div>

                    <div class="time-slot-section">
                        <div class="slot-header">
                            <h3>Night Slot (8 PM - 11:30 PM)</h3>
                        </div>
                        <div class="slot-options" id="night-slots">
                            <label class="slot-item">
                                <input type="checkbox" value="8:00-8:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                8:00-8:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="8:30-9:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                8:30-9:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="9:00-9:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                9:00-9:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="9:30-10:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                9:30-10:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="10:00-10:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                10:00-10:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="10:30-11:00" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                10:30-11:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="11:00-11:30" data-type="30min"
                                    onchange="updateSelectedSlots()">
                                11:00-11:30
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="8:00-9:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                8:00-9:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="9:00-10:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                9:00-10:00
                            </label>
                            <label class="slot-item">
                                <input type="checkbox" value="10:00-11:00" data-type="1hr"
                                    onchange="updateSelectedSlots()">
                                10:00-11:00
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
                <button class="btn btn-add" type="submit">Save</button>
                <button class="btn btn-cancel">Cancel</button>
            </div>
        </div>
    </div>
    <script src="../js/game_form.js"></script>
</body>

</html>