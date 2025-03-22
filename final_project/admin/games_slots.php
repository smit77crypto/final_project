<?php
// Fetch game data from the API
$api_url = "http://localhost/final_project/final_project/Api's/game_data.php";
$response = file_get_contents($api_url);
$games = json_decode($response, true);

// Randomly select a few games as "Most Popular"
$popularGames = [];
if (!empty($games)) {
    shuffle($games); // Shuffle array to randomize
    $popularGames = array_slice($games, 0, min(2, count($games))); // Pick only up to 2 games
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Games Page</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
    <style>
        :root {
            --primary-red: #4A5BE6;
            --dark-red: rgb(21, 41, 190);
            --light-bg: rgb(255, 255, 255);
            --light-grey: rgba(255, 255, 255, 0.87);
        }

        body {
            background: var(--light-bg);
            min-height: 100vh;
        }

        .container {
            margin-top: -20px;
            padding: 6rem;
        }

        .games-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 2rem;
            padding: 1rem;
        }

        .game-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            border: none;
        }

        .game-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(37, 87, 224, 0.2);
        }

        .card-image {
            height: 250px;
            position: relative;
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .game-card:hover .card-image img {
            transform: scale(1.05);
        }

        .card-content {
            /* padding: 1.5rem; */
            position: relative;
        }

        .game-title {
            color: #2d2d2d;
            font-weight: 700;
            margin-top: 10px;
            /* margin-bottom: 1rem; */
            font-size: 2rem;
            text-align: center;
            text-decoration: none !important;
        }

        .price-tag {

            /* background: var(--primary-red); */
            color: #2d2d2d;
            padding: 0.5rem 1rem;
            /* border-radius: 25px; */
            /* display: inline-flex;
            align-items: start; */
            margin: 0.5rem 0;
            transition: background 0.3s;
        }

        .price-tag i,
        span {
            /* margin-right: 0.5rem; */
            flex-direction: column;
            font-size: 1.3rem;
        }

        /* .price-tag:hover {
            background: var(--dark-red);
        } */

        .time-option {
            display: flex;
            justify-content: space-between;
            margin: 0.8rem 0;
        }

        .popular-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--primary-red);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Search Bar Styles */
        .search-container {
            display: grid;
            background-color: rgba(235, 231, 231, 0.87);
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            transition: box-shadow 0.3s;
            color: #6B7280;
            padding: 0.55rem;
            margin-bottom: 1.25rem;
            border: 1px solid #D1D5DB;
            background-color: #F3F4F6;
            border-radius: 0.75rem;
        }

        .search-container:focus-within {
            box-shadow: 0 3px 8px rgb(150, 150, 150);
        }

        .search-container div {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .search-bar {
            width: 100%;
            /* padding: 0.5rem 1rem; */
            background-color: transparent;
            /* border: 2px solid var(--primary-red); */
            /* border-radius: 25px; */
            font-size: 1.2rem;
            border: none;
            color: gray;
        }

        .search-bar:focus {
            outline: none;
            border: none;
        }

        .search-icon {

            /* margin-left: 1rem; */
            color: gray;
            cursor: pointer;
            font-size: 1.2rem;

        }

        .clear-icon {
            color: gray;
            float: right;
            cursor: pointer;
            font-size: 1.2rem;
            /* margin-right: 1rem; */
        }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container">
        <h1 class="text-center  display-4 fw-bold" style="color: var(--primary-red);">
            <i class="fas fa-gamepad me-3"></i>Games
        </h1>

        <!-- Search Bar -->
        <div class="games-container">
            <div class="search-container">
                <div style="position: relative; width: 100%;">
                    
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" class="search-bar" placeholder="Search games...">
                  
                    <i class="fas fa-times clear-icon" id="clearSearch"></i>
                </div>
            </div>
        </div>


        <div class="games-container" id="gamesContainer">
            <?php if (!empty($games)): ?>
                <?php foreach ($games as $game): ?>
                    <a href="slots.php?game_id=<?= $game['id'] ?>" class="game-card text-decoration-none">
                        <div class="card-image">
                            <img src="http://localhost/final_project/final_project/admin/<?= $game['card_image'] ?>"
                                alt="<?= $game['name'] ?>">
                            <?php if (in_array($game, $popularGames)): ?>
                                <div class="popular-badge">Most Popular</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <h3 class="game-title"><?= strtoupper($game['name']) ?></h3>
                            <div class="d-flex flex-column">
                                <div class="price-tag">
                                    <!-- <i class="fas fa-coins"></i> -->
                                    <span>₹<?= $game['half_hour'] ?> /30mins</span><br>
                                    <!-- </div>
                                <div class="price-tag"> -->
                                    <!-- <i class="fas fa-clock"></i> -->
                                    <span>₹<?= $game['hour'] ?> /1hr</span>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <h3 class="text-muted">No games available at the moment</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Live Search Functionality
            $('#searchInput').on('input', function () {
        const searchTerm = $(this).val().toLowerCase();
        const $games = $('.game-card');
        let gamesFound = false;

        $games.each(function () {
            const gameName = $(this).find('.game-title').text().toLowerCase();
            if (gameName.includes(searchTerm)) {
                $(this).show();
                gamesFound = true; // At least one match found
            } else {
                $(this).hide();
            }
        });

        if (!gamesFound) {
            if ($('#noGamesMessage').length === 0) { // Avoid duplicate messages
                $('<div id="noGamesMessage" class="no-games-message" style="text-align:center">No games found.</div>').appendTo('#gamesContainer');
            }
        } else {
            $('#noGamesMessage').remove(); // Remove the message if matches are found
        }
    });

            // Clear Search Input
            $('#clearSearch').on('click', function () {
                $('#searchInput').val('').trigger('input');
                $(this).hide();
            });
        });
    </script>
</body>

</html>