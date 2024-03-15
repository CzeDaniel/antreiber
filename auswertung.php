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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Auswertung</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background-color: black;
        }
    </style>
</head>
<body>
    <div class="container text-center border bg-danger" style="height: 100%;">
        <div class="container bg-primary" style="height: 10%;">
            <h1 class="p-4">Auswertung</h1>
        </div>

        <div class="container bg-info" style="height: 90%;">
            <div class="row justify-content-center">
                <table class="table table-striped table-dark">
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
            <button onclick="goBack()">Eine Seite zurück</button>
            
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
