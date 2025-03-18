<?php
// Fetch game data from the API
$api_url = "http://192.168.0.130/final_project/final_project/Api's/game_data.php";
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
            --dark-red:rgb(21, 41, 190);
            --light-bg: rgb(255, 255, 255);
        }

        body {
            background: var(--light-bg);
            min-height: 100vh;
        }

        .container {
            padding-top: 100px; /* Adjust for navbar height */
        }

        .games-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 2rem;
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
            height: 220px;
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
            padding: 1.5rem;
            position: relative;
        }

        .game-title {
            color: #2d2d2d;
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.25rem;
            text-decoration: none !important;
        }

        .price-tag {
            background: var(--primary-red);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            display: inline-flex;
            align-items: center;
            margin: 0.5rem 0;
            transition: background 0.3s;
        }

        .price-tag i {
            margin-right: 0.5rem;
            font-size: 0.9rem;
        }

        .price-tag:hover {
            background: var(--dark-red);
        }

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

        @media (max-width: 1200px) {
            .games-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .games-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .games-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include "navbar.php"; ?>
    
    <div class="container">
        <h1 class="text-center mb-5 display-4 fw-bold" style="color: var(--primary-red);">
            <i class="fas fa-gamepad me-3"></i>Featured Games
        </h1>
        
        <div class="games-container">
            <?php if (!empty($games)): ?>
                <?php foreach ($games as $game): ?>
                    <a href="slots.php?game_id=<?= $game['id'] ?>" class="game-card text-decoration-none">
                        <div class="card-image">
                            <img src="http://192.168.0.130/final_project/final_project/admin/<?= $game['card_image'] ?>" alt="<?= $game['name'] ?>">
                            <?php if (in_array($game, $popularGames)): ?>
                                <div class="popular-badge">Most Popular</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <h3 class="game-title"><?= $game['name'] ?></h3>
                            <div class="d-flex flex-column">
                                <div class="price-tag">
                                    <i class="fas fa-coins"></i>
                                    <span>₹<?= $game['half_hour'] ?> / 30 mins</span>
                                </div>
                                <div class="price-tag">
                                    <i class="fas fa-clock"></i>
                                    <span>₹<?= $game['hour'] ?> / 1 hour</span>
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
</body>
</html>
