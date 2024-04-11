<?php
session_start();
require_once './phpScripts/dbCon.php';
if (!isset($_SESSION['user'])) {
    setcookie(session_name(), '', time() - 3600, '/');
    session_destroy();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = isset($_POST['email']) ? filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING) : '';
        $password = isset($_POST['password']) ? filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) : '';
        $login = logInBD($username, $password);
        if ($login !== false) {
            session_start();
            updateLogin($login['idUser']);
            $_SESSION['id'] = $login['idUser'];
            $_SESSION['user'] = $login['name'];
            header('Location: ./pages/home.php');
            exit();
        }
    }
} else {
    header('Location: ./pages/home.php');
    exit();
}
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <title>Log in</title>
</head>

<body id="body" class="d-flex align-content-center justify-content-center overflow-hidden">
    <form class="d-flex flex-wrap flex-row bg-dark col-12 col-sm-12 col-md-7 rounded p-3 position-absolute top-50 start-50 translate-middle z-3" method="POST">
        <img class="col-12 col-md-5 rounded" src="./imgs/logo.png" alt="logo"/>
        <div class="col-1"></div>
        <div class="col-12 col-md-6">
            <h1 class="text-light">Log in</h1>
            <div class="mb-3">
                <label for="customEmailInput" class="form-label text-light">Username or Email address</label>
                <input type="text" class="form-control bg-dark text-light border border-secondary"
                    id="customEmailInput" aria-describedby="emailHelp" name="email">
            </div>
            <div class="mb-3">
                <label for="customPasswordInput" class="form-label text-light">Password</label>
                <input type="password" class="form-control bg-dark text-light border border-secondary"
                    id="customPasswordInput" name="password">
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="showPasswordCheckbox" />
                <label class="form-check-label text-light" for="showPasswordCheckbox"> Show password </label>
            </div>
            <div class="mb-3 d-flex flex-column">
                <a href="#" class="mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Forgot password?</a>
                <div class="d-flex flex-row justify-content-between">
                    <button type="submit" class="btn btn-primary ">Submit</button>
                    <a id="goToButton" href="./pages/register.php" class="btn btn-danger ">Sign up</a>
                </div>
            </div>
        </div>
    </form>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="./phpScripts/resetPasswordSend.php" method="POST">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Forgot password</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="customEmailInput" class="form-label text-light">Username or Email address</label>
                            <input type="text" class="form-control bg-dark text-light border border-secondary"
                                id="customEmailInput" aria-describedby="emailHelp" name="email">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send mail</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="./js/index.js"></script>
</body>

</html>
