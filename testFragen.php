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
    <title>Testfragen</title>
  

    <script>
        console.log('script load ============')
        function gotoAnswer() {
            console.log('click go to answer button ============')
            window.location.href = 'auswertung.php';
        }
    </script>
</head>
<body>
    <div class="row">
        <?php
        // Your PHP code...
        $frageBoolean = ($_SESSION['frageId'] ?? 1) <= 50; // Set to true if there are more questions to answer, false otherwise
        // Rest of your PHP code...
        ?>
        <div class="header w3-round-large">
            <h1>Antreibertest - Schnell und kostenlos testen!</h1>
        </div>


        <div class="row">
            <div class="card w3-round-large">
                <div class="w3-row">
                    <div class="w3-display-container w3-center w3-mobile">
                        <h2>Willkommen zur Testfragen-Seite!</h2>
                        <p>Der Name ist: <span id="displayName"></span></p>
                        <p>Der Token ist: <span id="displayToken"></span></p>
                        <form method="post" action="">        
                    
                        <?php if ($frageBoolean) : ?>
                            <!-- Show content for answering questions -->
                        <div class="w3-center w3-round w3-container">
                            <p>Frage <?php echo $frageId; ?>/50</p>
                            <p>Kategorie: <?php echo $kategorieText; ?></p>
                            <p id="frageText"><?php echo $frageText; ?></p>
                            <p>1, 2, 3, 4, 5 als Button zum Auswählen</p>
                            <form method="post" action="">
                                <div class="radioBtn w3-row w3-center">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <div class="col-auto">
                                            <input type="radio" class="btn-check" name="antwort" id="option<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if ($selectedAnswer == $i) echo 'checked'; ?>>
                                            <label class="w3-button btn-outline-warning" for="option<?php echo $i; ?>"><?php echo $i; ?></label>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                                <div class="w3-row w3-center">
                                    <div class="w3-modal-content w3-animate-zoom">
                                        <button class="w3-left w3-button w3-round-large w3-dark-gray w3-hide-small" style="width:20%" type="submit" name="zurueck">Zurück</button>
                                    </div>
                                    <div class="w3-modal-content w3-animate-zoom">
                                        <button class="w3-right w3-button w3-round-large w3-dark-gray w3-hide-small" style="width:20%" type="submit" name="weiter">Weiter</button>
                                    </div>
                                </div>
                                <p id="frageCounter"></p>
                            </form>
                        </div>
                    </div>
                    <div class="w3-display-container w3-center w3-mobile">
                        <?php else: ?>                  
                            <div class="w3-center w3-modal-content w3-animate-zoom">
                                <!--
                                <form method="post" action="auswertung.php">
                                    <button type="submit" class="btn btn-primary"  onclick="gotoAnswer()">Test abgeben</button>
                                </form>
                                -->
                                <button class="w3-center w3-button w3-round-large w3-dark-gray" style="width:20%" type="submit">
                                    <a href="auswertung.php" class="text-white">Test abgeben</a>
                                </button>
                            </div> 
                        <?php endif;?> 
                    </div>  
                </div>
            </div>
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
