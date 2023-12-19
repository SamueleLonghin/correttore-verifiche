<!DOCTYPE html>

<?php
$baseDir = '/files';

if (isset($_GET['info'])) {
    echo phpinfo();
    die();
}
function getDirContents($dir, $bpath, $cpath = '', &$results = array())
{
    if (is_dir($dir)) {
        $files = scandir($dir);
        // Controllo se voglio ignorare la cartella 
        // (per ignorare la cartella basta creare all'interno un file chiamato '.explorer-exclude')
        if (in_array('.explorer-exclude', $files)) {
            return $results;
        }
        // Controllo se voglio che la cartella abbia una pagina tutta sua, 
        // ovvero non elenco tutti i file qui ma all'interno di un'altro menù 
        // (perˇ farlo basta creare all'interno un file chiamato '.explorer-recoursive')
        if (in_array('.explorer-recoursive', $files) && $bpath != $cpath) {
            $results['/index.php?dir=' . $cpath] = [true,$cpath];
            return $results;
        }
        // Comincio ad analizzare i file della cartella
        $cpath .= DIRECTORY_SEPARATOR;
        foreach ($files as  $value) {

            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

            if ($value != "." && $value != ".." &&  $value[0] != '.') {
                // Se il file è un file (non cartella) ed è un file html o php lo aggiungo alla lista
                if (!is_dir($path)) {
                    if (in_array(pathinfo($path, PATHINFO_EXTENSION), ['html', 'php']))
                        // Se si chiama index non aggiungo il nome ma utilizzo la cartella
                        if (pathinfo($path, PATHINFO_FILENAME) == 'index')
                            $results[$cpath] = [false,strtolower( $cpath)];
                        // else
                        //     $results[$cpath . $value] = $cpath . $value;
                } else
                    // Se il file è una cartella esploro la cartella per trovare altri file
                    getDirContents($path, $bpath, $cpath . $value, $results,);
            }
        }
    }

    return $results;
}
?>

<html>

<body>
    <?php
    $blocchi = [
        [
            'title' => "4 ASA",
            'path' => "/4asa",
            'class' => 'teoria'
        ],
        [
            'title' => "4 CSA",
            'path' => '/4csa',
            'class' => 'esercizi'
        ]
    ];

    if (isset($_GET['dir'])) {
        $dir = $_GET['dir'];
        $blocchi = [[
            'title' => "Cartella " . $dir,
            'path' => $dir,
            'class' => 'dir'
        ]];
    }


    foreach ($blocchi as $blocco) {
    ?>

        <div class="blocco-link <?= $blocco['class'] ?>">

            <h1><?= $blocco['title'] ?></h1>
            <?php
            $files = getDirContents($baseDir .   $blocco['path'],  $blocco['path'], $blocco['path']);
            asort($files);
            foreach ($files as $url => $label) {
                [$is_folder, $label] = $label;
                echo "$label";
                if(!$is_folder)
                    echo "<a href='/view.php?file={$url}index.php'> Ispeziona </a>";
                echo "<a href='$url'> Visualizza </a> <br>";
                echo "<br>";
            }
            ?>
        </div>
    <?php
    }
    ?>

</body>

</html>


<style>
    h1 {
        text-align: center;
    }

    body {
        height: 100vh;
    }

    .blocco-link {
        border-radius: 50px;
        padding: 50px 50px;
        margin-bottom: 20px;
        width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    .teoria {
        background-color: aqua;
    }

    .esercizi {
        background-color: plum;
    }

    .dir {
        background-color: bisque;
    }

    .blocco-link a {
        font-size: 20px;
    }

    .footer {
        /* position: fixed; */
        left: 0;
        right: 0;
        bottom: 0;
        /* width: 100%; */
        background-color: sandybrown;
        color: white;
        text-align: center;
        margin-bottom: 8px;
        padding-bottom: 8px;
    }

    @media screen and (max-width: 700px) {
        .blocco-link {
            width: 100%;
        }
    }

</style>