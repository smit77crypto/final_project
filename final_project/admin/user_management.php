<?php
// Database connection
include('connect_database.php');

// Initialize search term
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

// Fetch user data with JOIN on membership table
$sql = "SELECT r.*, m.name AS membership_name 
        FROM register r
        LEFT JOIN membership m ON r.membership_id = m.id
        WHERE r.deleteval = 1";

if (!empty($searchTerm)) {
    $sql .= " AND (";
    $searchFields = ["r.full_name", "r.email", "r.phone_no", "r.username", "m.name"];
    $conditions = [];

    foreach ($searchFields as $field) {
        $conditions[] = "$field LIKE '%$searchTerm%'";
    }

    // Use exact match for gender
    $conditions[] = "r.gender = '$searchTerm'";

    $sql .= implode(" OR ", $conditions) . ")";
}


$sql .= " ORDER BY r.full_name";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="path/to/bootstrap.css" />
    <!-- You can link Bootstrap or use custom styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/user_management.css">
</head>

<body>
    <?php include 'navbar.php' ?>
    <div class="uper">
        <div class="search-form">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by name"
                    value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit">Search</button>
                <a href="user_management.php">Clear</a>
            </form>
        </div>
        <div class="adduser right">
            <a href="user_management/user_form.php" style="text-decoration:none; color:white">
                <div class="btn">
                    <div><i class="fa-solid fa-user-plus"></i></div>
                    <div style="font-weight: bold">ADD USER</div>
                </div>
            </a>
        </div>
    </div>
    <div class="tablearea">
        <?php
        if ($result->num_rows > 0) {
            echo "<table border='1'>
                <tr>
                    <th>Action</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Phone no</th>
                    <th>Username</th>
                    <th>Membership</th>  
                </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
            <td class='action-buttons'>
                <a href='user_management/user_form.php?id=" . $row["id"] . "' class='edit'><i class='fa-solid fa-pencil'></i></a>
              <a href='#' class='delete' onclick='confirmDelete(" . $row["id"] . ")'>
                <i class='fas fa-trash-alt'></i>
            </a>
                <a href='user_management/view_user.php?id=" . $row["id"] . "' class='view'><i class='fa-solid fa-eye'></i></a>
            </td>
            <td class='sametd'>" . $row["full_name"] . "</td>
            <td class='sametd'>" . $row["email"] . "</td>
            <td class='sametd'>" . (($row["gender"] === 'Other') ? 'N/A' : $row["gender"]) . "</td>
            <td class='sametd'>" . $row['phone_no'] . "</td>
           
            <td class='sametd'>" . $row["username"] . "</td>

            <td class='sametd'>" . $row["membership_name"] . "</td>
            
        </tr>";

            }
            echo "</table>";
        } else {
            echo "No records found!";
        }

        $conn->close();
        ?>
    </div>
    <script>
        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = "user_management/delete_user.php?id=" + userId;
            }
        }
    </script>


</body>

</html>