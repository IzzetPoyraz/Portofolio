<?php

// Show all errors (for educational purposes)
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// Constanten (connectie-instellingen databank)
define ('DB_HOST', 'localhost:3306');
define ('DB_USER', 'data');
define ('DB_PASS', 'Izzetpoyraz2002@');
define ('DB_NAME', 'data');


date_default_timezone_set('Europe/Brussels');

// Verbinding maken met de databank
try {
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Verbindingsfout: ' . $e->getMessage();
    exit;
}

$name = isset($_POST['name']) ? (string)$_POST['name'] : '';
$message = isset($_POST['message']) ? (string)$_POST['message'] : '';
$msgName = '';
$msgMessage = '';

// form is sent: perform formchecking!
if (isset($_POST['btnSubmit'])) {

    $allOk = true;

    // name not empty
    if (trim($name) === '') {
        $msgName = 'Gelieve een naam in te voeren';
        $allOk = false;
    }

    if (trim($message) === '') {
        $msgMessage = 'Gelieve een boodschap in te voeren';
        $allOk = false;
    }

    // end of form check. If $allOk still is true, then the form was sent in correctly
    if ($allOk) {
        $stmt = $db->exec('INSERT INTO messages (sender, message, added_on) VALUES (\'' . $name . '\',\'' . $message . '\',\'' . (new DateTime())->format('Y-m-d H:i:s') . '\')');

        // the query succeeded, redirect to this very same page
        if ($db->lastInsertId() !== 0) {
            header('Location: formchecking_thanks.php?name=' . urlencode($name));
            exit();
        } // the query failed
        else {
            echo 'Databankfout.';
            exit;
        }

    }

}

?><!DOCTYPE html>
<html lang="nl">
<head>
    <title>Testform</title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="styles.css"/>
</head>
<body>

<main class="container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h1>Testform</h1>
        <p class="message">Alle velden zijn verplicht, tenzij anders aangegeven.</p>

        <div>
            <label for="name">Jouw naam</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" class="input-text"/>
            <span class="message error"><?php echo $msgName; ?></span>
        </div>

        <div>
            <label for="message">Boodschap</label>
            <textarea name="message" id="message" rows="5" cols="40"><?php echo $message; ?></textarea>
            <span class="message error"><?php echo $msgMessage; ?></span>
        </div>

        <input type="submit" id="btnSubmit" name="btnSubmit" value="Verstuur"/>
    </form>
</main>
</body>
</html>
