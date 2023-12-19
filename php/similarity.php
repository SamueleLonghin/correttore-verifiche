<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolo Similarità</title>
</head>
<body>
    <h1>Seleziona una cartella</h1>
    <form action="calcola_similarita.php" method="post">
        <label for="cartella">Cartella:</label>
        <select name="cartella" id="cartella" required>
            <?php
            function printDirectories($directory) {
                $contents = scandir($directory);
                foreach ($contents as $item) {
                    $path = $directory . '/' . $item;
                    if (is_dir($path) && $item != "." && $item != "..") {
                        echo '<option value="' . $path . '">' . $path . '</option>';
                        printDirectories($path); // Chiamata ricorsiva per le sottocartelle
                    }
                }
            }

            printDirectories(__DIR__);
            ?>
        </select>
        <button type="submit">Calcola Similarità</button>
    </form>
</body>
</html>
