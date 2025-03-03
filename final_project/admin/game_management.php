<?php
// Database connection
include('connect_database.php');

// Initialize search term
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Pagination variables
$recordsPerPage = 4; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

// Base query
$query = "SELECT * FROM games WHERE deleteval=1";

// Modify query if searching
if (!empty($searchTerm)) {
    $query .= " AND name LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
}

// Fetch total number of records
$totalRecordsQuery = "SELECT COUNT(*) as total FROM games WHERE deleteval=1";
if (!empty($searchTerm)) {
    $totalRecordsQuery .= " AND name LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
}

$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Modify query to include pagination
$query .= " LIMIT $offset, $recordsPerPage";

// Execute query
$result = $conn->query($query);

// Check for query errors
if (!$result) {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/user_management.css">
    <link rel="stylesheet" href="css/game_management.css">
</head>

<body>
    <?php include 'navbar.php' ?>
    <div class="uper">
        <div class="search-form">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by game name" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Search</button>
                <a href="game_management.php">Clear</a>
            </form>
        </div>
        <div class="adduser right">
            <a href="game_management/game_form.php" style="text-decoration:none;">
                <div class="btn"><i style="color:white" class="fa-solid fa-user-plus"></i> <strong>ADD GAME</strong></div>
            </a>
        </div>
    </div>

    <div class="tablearea">
        <?php if ($result->num_rows > 0) : ?>
            <!-- Desktop Table View -->
            <div class="desktop-view">
                <table border='1'>
                    <tr>
                        <th>Action</th>
                        <th>Name</th>
                        <th>Price (30 min)</th>
                        <th>Price (60 min)</th>
                        <th>Card Image</th>
                        <th>Slider Image</th>
                        <th>Slots</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class='action-buttons'>
                                <a href='game_management/game_form.php?id=<?php echo $row["id"] ?>' class='edit'><i class='fa-solid fa-pencil'></i></a>
                                <a href="javascript:void(0);" class="delete" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                <a href='game_management/view_game.php?id=<?php echo $row["id"] ?>' class='view'><i class='fa-solid fa-eye'></i></a>
                            </td>
                            <td><?php echo htmlspecialchars($row["name"]); ?></td>
                            <td><?php echo htmlspecialchars($row["half_hour"]); ?></td>
                            <td><?php echo htmlspecialchars($row["hour"]); ?></td>
                            <td><img src=<?php echo htmlspecialchars($row["card_image"]); ?> alt='Game Image' width='50'></td>
                            <td><img src=<?php echo htmlspecialchars($row["slider_image"]); ?> alt='Slider Image' width='50'></td>
                            <td><button class='view-slots-btn' data-game-id='<?php echo $row["id"]; ?>'><i class="fa-solid fa-eye"></i></button></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>

            <!-- Mobile and Tablet Card View -->
            <div class="mobile-view">
                <?php
                // Reset the result pointer to reuse the data
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) : ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row["name"]); ?></h5>
                            <p class="card-text"><strong>Price (30 min):</strong> <?php echo htmlspecialchars($row["half_hour"]); ?></p>
                            <p class="card-text"><strong>Price (60 min):</strong> <?php echo htmlspecialchars($row["hour"]); ?></p>
                            <p class="card-text"><strong>Card Image:</strong> <img src=<?php echo htmlspecialchars($row["card_image"]); ?> alt='Game Image' width='50'></p>
                            <p class="card-text"><strong>Slider Image:</strong> <img src=<?php echo htmlspecialchars($row["slider_image"]); ?> alt='Slider Image' width='50'></p>
                            <div class="action-buttons">
                                <a href='game_management/game_form.php?id=<?php echo $row["id"] ?>' class='edit'><i class='fa-solid fa-pencil'></i></a>
                                <a href="javascript:void(0);" class="delete" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                <a href='game_management/view_game.php?id=<?php echo $row["id"] ?>' class='view'><i class='fa-solid fa-eye'></i></a>
                                <button class='view-slots-btn' data-game-id='<?php echo $row["id"]; ?>'><i class="fa-solid fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination Links -->
            <div class="pagination">
                <?php if ($page > 1) : ?>
                    <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $searchTerm; ?>">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo $searchTerm; ?>" <?php echo ($page == $i) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages) : ?>
                    <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $searchTerm; ?>">Next</a>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <p>No records found!</p>
        <?php endif; ?>
    </div>

    <!-- Popup Modal -->
    <div id="slotModal" class="modal">
        <div class="modal-content">
            <span class="close-popup"><i class="fa-solid fa-eye-slash"></i></span>
            <h2>All Slots</h2>
            <div id="slotsContainer">Loading...</div>
        </div>
    </div>

    <!-- Background Blur Overlay -->
    <div id="overlay" class="overlay"></div>

    <script>
    function confirmDelete(gameId) {
        if (confirm("Are you sure you want to delete this game?")) {
            var url = "game_management/delete_game.php?id=" + gameId;
            console.log(url); // Log the URL to check if it's correct
            window.location.href = url;
        }
    }

    $(document).ready(function() {
        // Show popup and lock background
        $(".view-slots-btn").on("click", function() {
            var gameId = $(this).attr("data-game-id");

            $.ajax({
                url: "fetch_slots.php",
                type: "POST",
                data: {
                    game_id: gameId
                },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        $("#slotsContainer").html("<p>" + response.error + "</p>");
                    } else {
                        var slotText = response.slots.join(", "); // Join slots with commas
                        $("#slotsContainer").html("<div>" + slotText + "</div>");
                    }
                    $("#slotModal").show();
                    $("#overlay").show(); // Show overlay
                    $("body").css("overflow", "hidden"); // Lock scroll
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    alert("Failed to fetch slots.");
                }
            });
        });

        // Close popup and unlock background
        $(".close-popup, #overlay").on("click", function() {
            $("#slotModal").hide();
            $("#overlay").hide(); // Hide overlay
            $("body").css("overflow", "auto"); // Unlock scroll
        });
    });
    </script>
</body>

</html>