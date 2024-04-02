<?php
session_start();

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
    <div class="row">

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
                                <th scope="col">Kategorie</th>
                                <th scope="col">Antworten</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categoryCounts as $category => $count): ?>
                                <tr>
                                    <td><?php echo $category; ?></td>
                                    <td><?php echo $count; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <button class="w3-center w3-button w3-round-large w3-dark-gray" type="submit" class="btn btn-primary" onclick="goBack()">Eine Seite zurück</button>
            </div>
        </div>
    </div>
            
            <script>
                // Function to navigate back to testFragen.php with name and token parameters
                function goBack() {
                    const name = '<?php echo $_GET['name']; ?>';
                    const token = '<?php echo $_GET['token']; ?>';
                    window.location.href = 'testFragen.php?name=' + encodeURIComponent(name) + '&token=' + encodeURIComponent(token);
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



        </div>
    </div>
</body>
</html>
