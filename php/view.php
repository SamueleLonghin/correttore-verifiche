<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview and Code</title>
    <link rel="stylesheet" href="/path/to/highlight/styles/default.css">
    <script src="/path/to/highlight/highlight.pack.js"></script>
    <script>
        hljs.initHighlightingOnLoad();
    </script>
    <style>
        body {
            display: flex;
            margin: 0;
            padding: 0;
        }

        .preview {
            flex: 1;
            height: 100vh;
            /* overflow: hidden; */
            width: 50vw;
        }

        .code {
            flex: 1;
            width: 50vw;
            height: 100vh;
            overflow: auto;
            padding: 10px;
            background-color: #f4f4f4;
            white-space: pre-wrap;
        }
    </style>
</head>

<body>
    <div class="preview">
        <?php
        $baseFolder = '/files';
        // Controlla se il nome del file è stato passato come parametro GET
        if (isset($_GET['file'])) {
            $file = $_GET['file'];
            $filename = $baseFolder . $file;
            var_dump($filename);
            // Controlla che il file esista
            if (file_exists($filename) && pathinfo($filename, PATHINFO_EXTENSION) === 'php') {
                // Mostra il contenuto del file PHP
                highlight_file($filename);
            } else {
                echo "Il file specificato non esiste o non è un file PHP.";
            }
        } else {
            echo "Il nome del file non è stato fornito.";
        }
        ?>
    </div>
    <div class="code">
        <!-- <?php
                // Mostra il codice PHP del file con formattazione
                if (isset($file)) {
                    $filename = __DIR__ . $file;
                    var_dump($filename);
                    $code = file_get_contents($filename);
                    echo "<pre><code class='php'>" . htmlspecialchars($code) . "</code></pre>";
                }
                ?> -->
        <embed class="code" src="<?= $file ?>" />

    </div>
</body>

</html>


<script>
    // Accedi al documento all'interno dell'iframe
    var iframeDocument = iframe.contentDocument || iframe.contentWindow.document;

    // Seleziona tutti i moduli all'interno dell'iframe e aggiungi un gestore per l'evento submit
    var formsInsideIframe = iframeDocument.querySelectorAll('form');
    formsInsideIframe.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            // Tua logica di gestione del submit qui
            console.log('Modulo all\'interno dell\'iframe inviato');
            event.preventDefault(); // Per impedire l'invio del modulo normale
        });
    });


    $(document).on("click", "a", function() {
        //this == the link that was clicked
        var href = $(this).attr("href");
        alert("You're trying to go to " + href);
    });

    $(document).on("click", "input", function() {
        //this == the link that was clicked
        var href = $(this).attr("href");
        alert("You're trying to go to " + href);
    });
</script>