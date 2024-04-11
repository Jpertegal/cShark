<?php
require '../phpScripts/dbCon.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../phpScripts/logout.php");
    exit();
}
if (isset($_GET['idCurs'])) {
    $curs = getCourse($_GET['idCurs']);
    $propietari = getPropetari($_GET['idCurs']);
} else {
    header("Location: ./home.php");
    exit();
}
if (isset($_POST['nom']) && isset($_POST['desc']) && isset($_FILES["imagen"]) && isset($_GET['idCurs'])) {
    updateCourse($_POST['nom'], $_POST['desc'], $_FILES['imagen'], $_GET['idCurs']);
} elseif (isset($_POST['like'])) {
    $likeAlreadyInDataBase = alreadyLike($_SESSION['id'], $_GET['idCurs']);
    if ($likeAlreadyInDataBase == false) {
        insertarLike($_SESSION['id'], $_GET['idCurs'], 0);
    } elseif ($likeAlreadyInDataBase[0]['like'] == 1 && $_POST['like'] != 1) {
        updateLike($_SESSION['id'], $_GET['idCurs'], $_POST['like']);
        $likes = -1;
        $dislikes = 1;
        updateLikesInCurs($_GET['idCurs'], $likes);
        updateDisLikesInCurs($_GET['idCurs'], $dislikes);
        header('Refresh:0');
    } elseif ($likeAlreadyInDataBase[0]['like'] == -1 && $_POST['like'] != -1) {
        updateLike($_SESSION['id'], $_GET['idCurs'], $_POST['like']);
        $likes = 1;
        $dislikes = -1;
        updateLikesInCurs($_GET['idCurs'], $likes);
        updateDisLikesInCurs($_GET['idCurs'], $dislikes);
        header('Refresh:0');
    } elseif ($likeAlreadyInDataBase[0]['like'] == 0 && $_POST['like'] == 1) {
        updateLike($_SESSION['id'], $_GET['idCurs'], $_POST['like']);
        $likes = 1;
        updateLikesInCurs($_GET['idCurs'], $likes);
        header('Refresh:0');
    } elseif ($likeAlreadyInDataBase[0]['like'] == 0 && $_POST['like'] == -1) {
        updateLike($_SESSION['id'], $_GET['idCurs'], $_POST['like']);
        $dislikes = -1;
        updateDisLikesInCurs($_GET['idCurs'], $dislikes);
        header('Refresh:0');
    }
}

?>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/carrousel.scss">
    <title>Course</title>
</head>

<body id="body" class="overflow-x-hidden">
    <?php
    if ($_SESSION['id'] == $propietari['iduser']) {
        echo '  <form>
                <input type="hidden" value="' . $_GET['idCurs'] . '" name="idCurs">';
        if (isset($_GET['mode'])) {
            if ($_GET['mode'] == "edit") {
                echo '  <input type="hidden" value="view" name="mode">
                                <button class="edit btn btn-light position-fixed start-100 m-1">
                                    <img width="20" height="20" src="https://img.icons8.com/ios-glyphs/30/visible--v1.png" alt="visible--v1"/>
                                </button>';
            } else {
                echo '  <input type="hidden" value="edit" name="mode">
                                <button class="edit btn btn-light position-fixed start-100 m-1">
                                    <img width="20" height="20" src="https://img.icons8.com/material-rounded/24/pencil--v1.png" alt="pencil--v1"/>
                                </button>';
            }
        } else {
            echo '  <input type="hidden" value="edit" name="mode">
                                <button class="edit btn btn-light position-fixed start-100 m-1">
                                    <img width="20" height="20" src="https://img.icons8.com/material-rounded/24/pencil--v1.png" alt="pencil--v1"/>
                                </button>';
        }
        echo '</form>';
        if (isset($_FILES['video'])) {
            insertVideo($_FILES['video'], $_GET['idCurs']);
        }
    }
    if (isset($_GET['mode']) && $_SESSION['id'] == $propietari['iduser']) {
        if ($_GET['mode'] != 'edit') {
            printarCursSenseEditar($curs);
        } else {
            printarCursAmbForm($curs);
        }

    } else {
        printarCursSenseEditar($curs);

    }

    ?>;


    ?>
    <header class="h-25">
        <div class="position-fixed start-0 top-0 bg-light h-100 p-1 z-3">
            <a class="btn btn-light d-flex align-items-center" href="home.php">
                <img width="20" height="20" src="https://img.icons8.com/ios-filled/50/return.png" alt="return" />
            </a>
        </div>
    </header>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="../js/index.js"></script>
    <script>
        $('.carousel').carousel();
    </script>
</body>

</html>