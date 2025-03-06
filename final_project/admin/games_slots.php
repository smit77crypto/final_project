<?php
// Fetch game data from the API
$api_url = "http://192.168.0.130/final_project/final_project/Api's/game_data.php";
$response = file_get_contents($api_url);
$games = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Games Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="path/to/bootstrap.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .games-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .game-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .game-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .game-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .game-card h3 {
            margin: 15px;
            font-size: 1.5rem;
            color: #333;
        }
        .game-card p {
            margin: 0 15px 15px;
            font-size: 1rem;
            color: #666;
        }
        .game-card .price {
            color: #ff4444;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include "navbar.php" ?>
    <h1>Available Games</h1>
    <div class="games-container">
        <?php
        if (!empty($games)) {
            foreach ($games as $game) {
                echo '
                <a href="slots.php?game_id=' . $game['id'] . '" class="game-card">
                    <img src="http://192.168.0.130/final_project/final_project/' . $game['card_image'] . '" alt="' . $game['name'] . '">
                    <h3>' . $game['name'] . '</h3>
                    <p><span class="price">₹' . $game['half_hour'] . '</span> / 30 mins</p>
                    <p><span class="price">₹' . $game['hour'] . '</span> / 1 hr</p>
                </a>';
            }
        } else {
            echo "<p>No games available.</p>";
        }
        ?>
    </div>
</body>
</html>