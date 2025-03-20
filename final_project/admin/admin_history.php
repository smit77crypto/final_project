<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="stylesheet" href="css/admin_history.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Booking History</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone No</th>
                    <th>Game Name</th>
                    <th>Slot</th>
                    <th>Booking Date</th>
                </tr>
            </thead>
            <tbody id="history-data">
                <!-- Data will be loaded here -->
            </tbody>
        </table>
        <div id="pagination"></div>
    </div>

    <script>
        $(document).ready(function () {
            loadHistory();

            function loadHistory(query = '', page = 1) {
                $.ajax({
                    url: "http://192.168.0.130/final_project/final_project/Api's/admin_history.php",
                    method: 'GET',
                    data: { token: '', query: query, page: page },
                    success: function (response) {
                        if (response.status === 'success') {
                            let rows = '';
                            $.each(response.data, function (index, booking) {
                                let status = booking.deleted == 1 ? 'Deleted' : 'Active';
                                rows += `<tr>
                                    <td>${booking.username}</td>
                                    <td>${booking.email}</td>
                                    <td>${booking.phone_no}</td>
                                    <td>${booking.game_name}</td>
                                    <td>${booking.slot}</td>
                                    <td>${booking.book_date}</td>
                                </tr>`;
                            });
                            $('#history-data').html(rows);
                        } else {
                            alert('Failed to load data.');
                        }
                    }
                });
            }

            $('#search').on('keyup', function () {
                const query = $(this).val();
                loadHistory(query);
            });
        });
    </script>
</body>
</html>
