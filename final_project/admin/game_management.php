<?php
// Database connection
include('connect_database.php');

// Initialize search term
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Base query
$query = "SELECT * FROM games WHERE deleteval=1";

// Modify query if searching
if (!empty($searchTerm)) {
    $query .= " AND name LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
}

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
                <input type="text" name="search" placeholder="Search by game name"
                    value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Search</button>
                <a href="game_management.php">Clear</a>
            </form>
        </div>
        <div class="adduser right">
            <a href="game_management/game_form.php" style="text-decoration:none; ">
                <div class="btn"><i style="color:white"class="fa-solid fa-user-plus"></i> <strong >ADD GAME</strong></div>
            </a>
        </div>
    </div>

    <div class="tablearea">
        <?php if ($result->num_rows > 0): ?>
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
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td class='action-buttons'>
                    <a href='game_management/game_form.php?id=<?php echo $row["id"] ?>' class='edit'><i
                            class='fa-solid fa-pencil'></i></a>
                    <a href="javascript:void(0);" class="delete" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                    <a href='game_management/view_game.php?id=<?php echo $row["id"] ?>' class='view'><i
                            class='fa-solid fa-eye'></i></a>
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
        <?php else: ?>
        <p>No records found!</p>
        <?php endif; ?>
    </div>

    <div id="slotModal" class="modal">
        <div class="modal-content">
            <span class="close-popup"><i class="fa-solid fa-eye-slash"></i></span>
            <h2>All Slots</h2>
            <div id="slotsContainer">Loading...</div>
        </div>
    </div>

    <script>
    function confirmDelete(gameId) {
        if (confirm("Are you sure you want to delete this game?")) {
            var url = "game_management/delete_game.php?id=" + gameId;
            console.log(url); // Log the URL to check if it's correct
            window.location.href = url;
        }
    }


    $(document).ready(function() {
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

                        // Adjust popup height dynamically
                        var slotCount = response.slots.length;
                        var height = Math.min(100 + slotCount * 10,
                            400); // Max height 400px
                        $("#slotModal").css("height", height + "px");
                    }
                    $("#slotModal").show();
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    alert("Failed to fetch slots.");
                }
            });
        });

        $(".close-popup").on("click", function() {
            $("#slotModal").hide();
        });
    });
    </script>
</body>

</html>