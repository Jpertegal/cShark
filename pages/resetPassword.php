<?php

include '../phpScripts/dbCon.php';
$connected = verifyResetCode();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    updatePassowrd($_POST['mail'], $_POST['password']);
    $connected = false;
}
?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/register.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Web Activation</title>
</head>

<body id="body" class="d-flex align-content-center justify-content-center overflow-hidden">
    <?php
    if ($connected) {
        echo '  <form
                    class="d-flex flex-wrap flex-row bg-dark col-12 col-sm-12 col-md-3 rounded p-3 position-absolute top-50 start-50 translate-middle z-3"
                    method="POST">
                    <div class="col-12 col-md-12">
                        <div class="mb-3">
                            <label for="customEmailInput" class="form-label text-light">Please wirte the new password</label>
                            <input type="password" class="form-control bg-dark text-light border border-secondary" name="password">
                        </div>
                        <input type="hidden" name="mail" value="' . $_GET['mail'] . '">
                        <button type="submit" class="btn btn-primary ">Submit</button>
                    </div>
                </form>';
    } else {
        echo '<h1 class="text-light">No va bien esto eh</h1>';
    }
    ?>
    <div class="col-12 position-absolute top-100 waves-container z-3">
        <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
            <defs>
                <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
            </defs>
            <g class="parallax">
                <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
                <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
                <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
                <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
            </g>
        </svg>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="./js/index.js"></script>
</body>

</html>