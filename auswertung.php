<?php
session_start();

// Assuming you have a database with name 'your_database' and a table named 'fragen'
$servername = "localhost:3306";
$username = "antreiber_admin";
$password = "tiP#3454oRZunhron";
$database = "antreibertest";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the session data is available
if (!isset($_SESSION['selectedAnswers'])) {
    header("Location: testFragen.php"); // Redirect if session data is not available
    exit();
}

// Initialize array to store category counts
$categoryCounts = [];

// Loop through selected answers and count them by category
foreach ($_SESSION['selectedAnswers'] as $category => $answers) {
    foreach ($answers as $answer) {
        if (!isset($categoryCounts[$category])) {
            $categoryCounts[$category] = 0;
        }
        $categoryCounts[$category] += intval($answer);
    }
}

// Sort categories in ascending order
ksort($categoryCounts);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-flat.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-flat.css">
    <link rel="stylesheet" href="style.css">
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>-->
    <title>Auswertung</title>
</head>
<body>

    <main>
    <div class="header w3-round-large">
        <h1>Ergebnis Antreibertest</h1>
    </div>

    <div class="w3-center">
        <div class="card w3-round-large w3-center">
            <div class="w3-center w3-row">
                <div class="w3-center w3-container w3-display-container">
                <table class="w3-table-all w3-hoverable w3-centered">
                    <thead>
                        <tr>
                            <th scope="col">Antreibertypen & Punktzahl</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categoryCounts as $category => $count): ?>
                            <?php
                            $getcategoryQuery = "SELECT antreibertyp FROM antreiberarten WHERE ID = $category";
                            $getCategoryresults = $conn->query($getcategoryQuery);
                            $categoryName = $getCategoryresults->fetch_assoc()['antreibertyp'];

                            $getbeschreibungQuery = "SELECT beschreibung FROM antreiberarten WHERE ID = $category";
                            $getBeschreibungresults = $conn->query($getbeschreibungQuery);
                            $categoryBeschreibung = $getBeschreibungresults->fetch_assoc()['beschreibung'];

                            $getverhaltensweisequery = "SELECT verhaltensweisen FROM antreiberarten WHERE ID = $category";
                            $getverhaltensweiseresults = $conn->query($getverhaltensweisequery);
                            $categoryVerhaltensweise = $getverhaltensweiseresults->fetch_assoc()['verhaltensweisen'];
                            ?>
                            <tr>
                                <td colspan="2">
                                    <details>
                                        <summary><?php echo $category . ': ' . $categoryName . ' (' . $count . ' Punkte)'; ?></summary>
                                        <h4>Beschreibung:</h4>
                                        <p class="w3-left-align"><?php echo $categoryBeschreibung ?></p>
                                        <h4>Verhaltensweise:</h4>
                                        <p class="w3-left-align"><?php echo $categoryVerhaltensweise ?></p>
                                    </details>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="w3-center">
        <div class="card w3-round-large w3-center">
            <div class="w3-center w3-row">
                <div class="w3-center w3-container w3-display-container">
                    <p class="w3-start">Auswertung:<br>
                    10 - 29 Punkte: förderlich<br>
                    30 - 39 Punkte: möglicherweise beeinträchtigend<br>
                    40 - 50 Punkte: möglicherweise gesundheitsgefährdend</p>
                    <button class="w3-center w3-button w3-round-large w3-dark-gray w3-hide-small" style="width:20%" type="submit" class="btn btn-primary" onclick="goBack()">Neustart</button>
                    <button class="w3-center w3-button w3-round-large w3-dark-grey w3-hide-medium w3-hide-large" style="width:40%" type="submit" class="btn btn-primary" onclick="goBack()">Neustart</button>
                </div>
            </div>
        </div>
    </div>
    </main>

    <footer>
		<div class="row">
			<div class="card w3-round-large">
				<div class="w3-row w3-center">
                    <p>©️ Frederic Gritz, Daniel Czeguhn <br> Antreibertest 2024</p>
				</div>
			</div>
		</div>
	</footer>
            
    <script>
        // Function to navigate back to testFragen.php with name and token parameters
        function goBack() {
            const name = '<?php echo $_GET['name']; ?>';
            const token = '<?php echo $_GET['token']; ?>';
            window.location.href = '/';
        }
    </script>
            
            <!-- Template to go back to test when finished.
            <script>
                // Function to navigate back to testFragen.php with name and token parameters
                function goBack() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const name = urlParams.get('name');
                    const token = urlParams.get('token');
                    
                    // Check if both name and token are not null or empty
                    if (name && token) {
                        const redirectUrl = `testFragen.php?name=${encodeURIComponent(name)}&token=${encodeURIComponent(token)}`;
                        window.location.href = redirectUrl;
                    } else {
                        // Redirect without parameters if name or token is missing
                        window.location.href = 'testFragen.php';
                    }
                }
            </script>
            -->       
        
</body>
</html>
