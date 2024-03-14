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

// Function to get a question from the database based on the given question ID
function getFrage($conn, $frageId) {
    $sql = "SELECT * FROM fragestellung WHERE id = $frageId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['frage'];
    } else {
        return "Frage not found";
    }
}

// Initialize array to store selected answers
$_SESSION['selectedAnswers'] = $_SESSION['selectedAnswers'] ?? [];

// Handle form submissions for Weiter and Zurück buttons
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["weiter"])) {
        // Save selected answer
        $_SESSION['selectedAnswers'][$_SESSION['frageId']] = $_POST['antwort'];
        $_SESSION['frageId'] = ($_SESSION['frageId'] ?? 1) + 1;
    } elseif (isset($_POST["zurueck"]) && $_SESSION['frageId'] > 1) {
        $_SESSION['frageId']--;
    }
}

$frageId = $_SESSION['frageId'] ?? 1; // Start with Frage 1 if not set
$frageText = getFrage($conn, $frageId);

// Get selected answer if available
$selectedAnswer = $_SESSION['selectedAnswers'][$frageId] ?? null;

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
            
                <p>1,2,3,4,5 als button zum auswählen</p>
                <div class="row justify-content-center">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="col-auto">
                        <input type="radio" class="btn-check" name="antwort" id="option<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if ($selectedAnswer == $i) echo "checked"; ?>>
                        <label class="btn btn-outline-warning" for="option<?php echo $i; ?>"><?php echo $i; ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
                <p></p>
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
