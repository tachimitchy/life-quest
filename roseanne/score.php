
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stage Complete</title>
</head>
<body>

    <h1>Stage Complete!</h1>

    <div id="completionScreen">

        <p>Score: <span id="score">8500</span></p>

        <p>Time: <span id="time">02:12</span></p>

        <p>High Score: <span id="highScore">8500</span></p>

        <button onclick="viewLeaderboard()">
            View Leaderboard
        </button>

        <button onclick="backToDashboard()">
            Back to Dashboard
        </button>

    </div>

    <div id="leaderboardScreen" hidden>

        <h2>Leaderboard</h2>

        <ol id="leaderboardList"></ol>

        <button onclick="backToResults()">
            Back
        </button>

    </div>

    <div id="dashboardScreen" hidden>

        <h2>Dashboard</h2>

        <p>Welcome back, Player!</p>

        <p>Your High Score: <span id="dashboardScore"></span></p>

        <button onclick="backToResults()">
            Back to Results
        </button>

    </div>

    <script>

        // Game data
        let gameData = {
            score: 8500,
            time: "02:12",
            highScore: 8500
        };

        // Leaderboard data
        let leaderboard = [
            { name: "You", score: 8500 },
            { name: "Player 2", score: 7900 },
            { name: "Player 3", score: 7200 },
            { name: "Player 4", score: 6800 }
        ];

        // Display game results
        function updateResults() {

            if (gameData.score > gameData.highScore) {
                gameData.highScore = gameData.score;
            }

            document.getElementById("score").textContent =
                gameData.score.toLocaleString();

            document.getElementById("time").textContent =
                gameData.time;

            document.getElementById("highScore").textContent =
                gameData.highScore.toLocaleString();

            document.getElementById("dashboardScore").textContent =
                gameData.highScore.toLocaleString();

        }

        // Show leaderboard
        function viewLeaderboard() {

            const list = document.getElementById("leaderboardList");

            list.innerHTML = "";

            leaderboard.sort((a, b) => b.score - a.score);

            leaderboard.forEach(player => {

                const item = document.createElement("li");

                item.textContent =
                    player.name + " - " +
                    player.score.toLocaleString();

                list.appendChild(item);

            });

            showScreen("leaderboardScreen");

        }

        // Navigate to dashboard
        function backToDashboard() {

            showScreen("dashboardScreen");

        }

        // Return to results
        function backToResults() {

            showScreen("completionScreen");

        }

        // Screen navigation
        function showScreen(screenId) {

            const screens = [
                "completionScreen",
                "leaderboardScreen",
                "dashboardScreen"
            ];

            screens.forEach(id => {

                document.getElementById(id).hidden =
                    id !== screenId;

            });

        }

        // Initialize
        updateResults();

    </script>

</body>
</html>
