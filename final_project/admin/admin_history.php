<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_history.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Booking History</h2>
        
        <!-- Search and Tabs Container -->
        <div class="controls">
            <div class="search-container">
                <div class="search-input">
                    <input type="text" id="search" placeholder="Search bookings...">
                    <i class="fas fa-times clear-icon"></i>
                </div>
            </div>
            <div class="tabs">
                <div class="tab active" data-view="past">Past Bookings</div>
                <div class="tab" data-view="canceled">Canceled Slots</div>
            </div>
        </div>

        <!-- Cards Container -->
        <div class="cards-container" id="history-data">
            <!-- Cards will be loaded here dynamically -->
        </div>

        <!-- Pagination -->
        <div id="pagination"></div>
    </div>

    <script>
        $(document).ready(function () {
            let currentView = 'past';
            let allData = { past: [], canceled: [] };
            let currentPage = 1;
            const itemsPerPage = 12;

            // Load initial data
            loadData();

            function loadData() {
                $.ajax({
                    url: "http://192.168.0.130/final_project/final_project/Api's/admin_history.php",
                    method: 'GET',
                    success: function (response) {
                        if (response.status === 'success') {
                            allData = {
                                past: response.data || [],
                                canceled: response.cancle || []
                            };
                            updateDisplay();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("API Error:", status, error);
                    }
                });
            }

            function updateDisplay() {
                const searchQuery = $('#search').val().toLowerCase();
                let filteredData = allData[currentView].filter(item => 
                    Object.values(item).some(value =>
                        String(value).toLowerCase().includes(searchQuery)
                    )
                );

                // Pagination
                const totalPages = Math.ceil(filteredData.length / itemsPerPage);
                const start = (currentPage - 1) * itemsPerPage;
                const paginatedData = filteredData.slice(start, start + itemsPerPage);

                renderCards(paginatedData);
                renderPagination(totalPages);
            }

            function renderCards(data) {
                let cards = '';
                data.forEach(booking => {
                    cards += `
                        <div class="card">
                            <div class="card-header">
                                <span class="username">${booking.username}</span>
                                <span class="status ${currentView}">
                                    ${currentView === 'past' ? 'Booked' : 'Canceled'}
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <span class="label">Email</span>
                                    <span class="value">${booking.email}</span>
                                </div>
                                <div class="row">
                                    <span class="label">Phone</span>
                                    <span class="value">${booking.phone_no}</span>
                                </div>
                                <div class="row">
                                    <span class="label">Game</span>
                                    <span class="value">${booking.game_name}</span>
                                </div>
                                <div class="row">
                                    <span class="label">Slot</span>
                                    <span class="value">${booking.slot}</span>
                                </div>
                                <div class="row">
                                    <span class="label">Date</span>
                                    <span class="value">${booking.book_date}</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#history-data').html(cards || '<div class="no-results">No bookings found</div>');
            }

            function renderPagination(totalPages) {
                let pagination = '';
                for (let i = 1; i <= totalPages; i++) {
                    pagination += `
                        <button class="page-item ${i === currentPage ? 'active' : ''}" data-page="${i}">
                            ${i}
                        </button>
                    `;
                }
                $('#pagination').html(pagination);
            }

            // Event Listeners
            $('.tab').on('click', function () {
                currentView = $(this).data('view');
                currentPage = 1;
                $('.tab').removeClass('active');
                $(this).addClass('active');
                updateDisplay();
            });

            $('#search').on('input', () => {
                currentPage = 1;
                updateDisplay();
            });

            $('.clear-icon').on('click', function() {
                $('#search').val('').trigger('input');
            });

            $(document).on('click', '.page-item', function () {
                currentPage = parseInt($(this).data('page'));
                updateDisplay();
            });
        });
    </script>
</body>
</html>