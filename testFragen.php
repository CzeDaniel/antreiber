<?php
// Set cache control headers to prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

session_start();

// Enable error reporting for debugging
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

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
    } elseif (isset($_POST["test_abgeben"])) {
        // Save selected answer for the last question
        $selectedAnswer = $_POST['antwort'];
        $frageId = $_SESSION['frageId'] ?? 1;
        $kategorie = getCategory($conn, $frageId);
        $_SESSION['selectedAnswers'][$kategorie][] = $selectedAnswer;
        
        // Reset $frageId to 1 when Test Abgeben is clicked
        $_SESSION['frageId'] = 1;
        // Redirect to auswertung.php
        header("Location: auswertung.php");
        exit();
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
    <title>Fragen</title>
  

    <script>
        console.log('script load ============')
        function gotoAnswer() {
            console.log('click go to answer button ============')
            window.location.href = 'auswertung.php';
        }
    </script>
</head>
<body>

    <main>
    <div class="row">
        <?php
        $frageBoolean = ($_SESSION['frageId'] ?? 1) <= 50; // Set to true if there are more questions to answer, false otherwise
        ?>
        <div class="header w3-round-large">
            <h1>Antreibertest</h1>
        </div>
                    
        <div class="row">
            <div class="card w3-round-large">
                <div class="w3-row">
                        <?php if ($frageBoolean) : ?>
                            <!-- Show content for answering questions -->
                            <div class="w3-center w3-round w3-container">
                                <!-- <p>Kategorie: <?php // echo $kategorieText; ?></p> -->
                                <p class="frageText" id="frageText"><?php echo $frageText; ?></p>
                                <form method="post" action="">
                                    <div class="radioBtn w3-row w3-center w3-hide-small">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <div class="col-auto">
                                                <input type="radio" class="btn-check" name="antwort" id="option<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if ($selectedAnswer == $i) echo 'checked'; ?>>
                                                <label class="w3-button btn-outline-warning" for="option<?php echo $i; ?>"><?php echo $i; ?></label>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="radioBtn w3-row w3-center w3-hide-medium w3-hide-large">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <div class="col-auto" style="margin-right: 4px;">
                                                <input type="radio" class="btn-check" name="antwort" id="option<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if ($selectedAnswer == $i) echo 'checked'; ?>>
                                                <div>
                                                    <label class="w3-button btn-outline-warning" style="pointer-events: none;" for="option<?php echo $i; ?>"><?php echo $i; ?></label>
                                                    <br>
                                                </div>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="w3-row w3-center">
                                        <?php if ($frageId == 50) : ?>
                                            <div class="w3-center w3-row">
                                                <div class="w3-animate-zoom w3-third">
                                                    <button class="w3-button w3-round-large w3-dark-gray w3-hide-small w3-right" style="width:30%" type="submit" name="zurueck">Zurück</button>
                                                </div>
                                                <div class="w3-animate-zoom  w3-third">
                                                    <p class="w3-center w3-hide-small">Frage <?php echo $frageId; ?>/50</p>
                                                </div>
                                                <div class="w3-animate-zoom w3-third">
                                                    <button class="w3-button w3-round-large w3-dark-gray w3-hide-small w3-left" style="width:30%" type="submit" name="test_abgeben" onclick="gotoAnswer()">Abgabe</button>
                                                </div>
                                                <div class="w3-animate-zoom">
                                                    <button class="w3-button w3-round-large w3-dark-gray w3-hide-medium w3-hide-large w3-left" style="width:30%" type="submit" name="zurueck">Zurück</button>
                                                </div>
                                                <div class="w3-animate-zoom">
                                                    <button class="w3-button w3-round-large w3-dark-gray w3-hide-medium w3-hide-large w3-right" style="width:30%" type="submit" name="test_abgeben" onclick="gotoAnswer()">Abgabe</button>
                                                </div>
                                            </div>
                                            <div class="w3-center w3-row w3-hide-medium w3-hide-large">
                                                    <div class="w3-animate-zoom">
                                                        <p class="w3-center w3-hide-medium w3-hide-large">Frage <?php echo $frageId; ?>/50</p>
                                                </div>
                                            <?php else: ?>
                                            <div class="w3-center w3-row">
                                                <div class="w3-animate-zoom w3-third">
                                                    <button class="w3-button w3-round-large w3-dark-gray w3-hide-small w3-right" style="width:30%" type="submit" name="zurueck">Zurück</button>
                                                </div>
                                                <div class="w3-animate-zoom w3-third">
                                                    <p class="w3-center w3-hide-small">Frage <?php echo $frageId; ?>/50</p>
                                                </div>
                                                <div class="w3-animate-zoom w3-third">
                                                    <button class="w3-button w3-round-large w3-dark-gray w3-hide-small w3-left" style="width:30%" type="submit" name="weiter">Weiter</button>
                                                </div>
                                                <div class="w3-animate-zoom">
                                                    <button class="w3-button w3-round-large w3-dark-gray w3-hide-medium w3-hide-large w3-left" style="width:30%" type="submit" name="zurueck">Zurück</button> 
                                                </div>
                                                <div class="w3-animate-zoom">
                                                    <button class="w3-button w3-round-large w3-dark-gray w3-hide-medium w3-hide-large w3-right" style="width:30%" type="submit" name="weiter">Weiter</button>
                                                </div>
                                            </div>
                                                <div class="w3-center w3-row w3-hide-medium w3-hide-large">
                                                    <div class="w3-animate-zoom">
                                                        <p class="w3-center w3-hide-medium w3-hide-large">Frage <?php echo $frageId; ?>/50</p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </div>
                        <?php endif;?>
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
