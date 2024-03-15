<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Initialize $selectedAnswer variable
$selectedAnswer = null;

// Function to get a question from the database based on the given question ID
function getFrage($conn, $frageId) {
    $sql = "SELECT * FROM fragestellung WHERE id = $frageId";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['frage'];
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return "Frage not found";
    }
}

// Function to get a question from the database based on the given question ID
function getCategory($conn, $frageId) {
    $sql = "SELECT kategorie FROM fragestellung WHERE id = $frageId";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['kategorie'];
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return "Frage not found";
    }
}

// Initialize array to store selected answers
$_SESSION['selectedAnswers'] = $_SESSION['selectedAnswers'] ?? [];

// Handle form submissions for Weiter and Zurück buttons
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["weiter"])) {
        // Save selected answer
        $selectedAnswer = $_POST['antwort'];
        $frageId = $_SESSION['frageId'] ?? 1;
        $kategorie = getCategory($conn, $frageId);
        $_SESSION['selectedAnswers'][$kategorie][] = $selectedAnswer;
        $_SESSION['frageId'] = ($frageId ?? 1) + 1;
    } elseif (isset($_POST["zurueck"]) && $_SESSION['frageId'] > 1) {
        // Remove last selected answer
        $frageId = $_SESSION['frageId'] ?? 1;
        $kategorie = getCategory($conn, $frageId - 1);
        array_pop($_SESSION['selectedAnswers'][$kategorie]);
        $_SESSION['frageId'] = ($frageId ?? 1) - 1;
    }
}

// Check if all questions are answered
$allQuestionsAnswered = ($_SESSION['frageId'] ?? 1) > 50;

// Get current question ID
$frageId = $_SESSION['frageId'] ?? 1;

// Reset selected answer when displaying a new question
$selectedAnswer = null;

// Get Frage and Kategorie
$frageText = getFrage($conn, $frageId);
$kategorieText = getCategory($conn, $frageId);


// Close the database connection when done
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Testfragen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container text-center border bg-danger" style="height: 100%;">
        <div class="container bg-primary" style="height: 10%;">
            <h1 class="p-4">Antreibertest - Schnell und kostenlos testen!</h1>
        </div>

        <div class="container bg-info" style="height: 90%;">
            <div class="row justify-content-center">
                <p class="p-1">Willkommen zur Testfragen-Seite! Der Name ist: <span id="displayName"></span></p>
                <p class="">Der Token ist: <span id="displayToken"></span></p>
            </div>
            <form method="post" action="">
            <div class="container border rounded-1 bg-danger" style="height: 40%;">
                <p class="pt-3">+++FRAGE+++</p>
                <p id="frageText"><?php echo $frageText; ?></p>
                <p><?php echo $kategorieText; ?></p>

                <p>1,2,3,4,5 als button zum auswählen</p>
                <div class="row justify-content-center">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <div class="col-auto">
                            <input type="radio" class="btn-check" name="antwort" id="option<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if ($selectedAnswer == $i) echo "checked"; ?>>
                            <label class="btn btn-outline-warning" for="option<?php echo $i; ?>"><?php echo $i; ?></label>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary" name="zurueck">Zurück</button>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary" name="weiter">Weiter</button>
                    </div>
                </div>
                <p id="frageCounter"></p>
            </div>
            </form>
            <?php if ($allQuestionsAnswered): ?>
                <div>
                    <form method="post" action="auswertung.php">
                        <button type="submit" class="btn btn-primary">Test abgeben</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        // Auslesen des Namens und des Tokens aus den URL-Parametern
        const urlParams = new URLSearchParams(window.location.search);
        const name = urlParams.get('name');
        const token = urlParams.get('token');

        // Anzeigen des Namens und des Tokens
        document.getElementById('displayName').innerText = name;
        document.getElementById('displayToken').innerText = token;
    </script>
</body>
</html>
