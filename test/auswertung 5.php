<?php
// Set cache control headers to prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

session_start();

// Assuming you have a database with name 'your_database' and a table named 'fragen'
$servername = "localhost:3306";
$username = "root";
$password = "";
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
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>-->
    <title>Auswertung</title>
</head>
<body>
    <div class="card w3-round-large w3-center">
        <h1>Auswertung</h1>
        <br>
    </div>

    <div class="w3-center">
        <div class="card w3-round-large w3-center">
            <div class="w3-center w3-row">
                <div class="w3-center w3-container w3-display-container">
                <table class="w3-table-all w3-hoverable w3-centered">
                    <thead>
                        <tr>
                            <th scope="col">Antreibertypen</th>
                            <th scope="col">Antworten</th>
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
                                        <table>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                            </tr>
                                        </table>
                                        <h4>Beschreibung:</h4>
                                        <p><?php echo $categoryBeschreibung ?></p>
                                        <h4>Verhaltensweise:</h4>
                                        <p><?php echo $categoryVerhaltensweise ?></p>
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
                    <button class="w3-center w3-button w3-round-large w3-dark-gray" style="width:20%" type="submit" class="btn btn-primary" onclick="goBack()">Test neu starten</button>
                </div>
            </div>
        </div>
    </div>
            
    <script>
        // Function to navigate back to testFragen.php with name and token parameters
        function goBack() {
            <?php unset($_SESSION['selectedAnswers']); ?>
            const name = '<?php echo $_GET['name']; ?>';
            const token = '<?php echo $_GET['token']; ?>';
            window.location.href = 'index.html';
            
        }
    </script>    
</body>
</html>
