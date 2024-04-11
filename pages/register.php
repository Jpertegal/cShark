<?php
require_once '../phpScripts/dbCon.php';
require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING) : '';
    $firstName = isset($_POST['firstname']) ? filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING) : '';
    $lastName = isset($_POST['lastname']) ? filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING) : '';
    $email = isset($_POST['email']) ? filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) : '';
    $pass = isset($_POST['password']) ? filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING) : '';
    $login = verificarUsuariBD($email);
    if ($login == false) {
        $result = insertarUsuari($username, $firstName, $lastName, $email, $pass);
        if ($result == 1) {
            header("Location: ../index.php?from=1");
            exit();
        }
    }
}
?>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/register.css">
    <title>Register</title>
</head>

<body id="body" class="d-flex align-content-center justify-content-center overflow-hidden">
    <form id="registerForm"
        class="d-flex flex-wrap flex-row bg-dark col-12 col-sm-12 col-md-4 rounded p-3 position-absolute top-50 start-50 translate-middle z-3"
        method="POST">
        <div class="col-1"></div>
        <div class="col-12">
            <h1 class="text-light">Register</h1>
            <div class="pe-2 h-75 mb-2">
                <div class="mb-3">
                    <label for="customUserNameInput" class="form-label text-light">Username</label>
                    <input type="text" class="form-control bg-dark text-light border border-secondary"
                        id="customUserNameInput" aria-describedby="emailHelp" name="username">
                </div>
                <div class="d-flex flex-row mb-3">
                    <div class="col-6 me-1">
                        <label for="customFirstNameInput" class="form-label text-light">First name</label>
                        <input type="text" class="form-control bg-dark text-light border border-secondary"
                            id="customFirstNameInput" aria-describedby="emailHelp" name="firstname">
                    </div>
                    <div class="col-6">
                        <label for="customLastNameInput" class="form-label text-light">Last name</label>
                        <input type="text" class="form-control bg-dark text-light border border-secondary"
                            id="customLastNameInput" aria-describedby="emailHelp" name="lastname">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="customEmailInput" class="form-label text-light">Email address</label>
                    <input type="email" class="form-control bg-dark text-light border border-secondary"
                        id="customEmailInput" aria-describedby="emailHelp" name="email">
                </div>
                <div class="d-flex flex-row mb-3">
                    <div class="mb-1 col-6 me-1">
                        <label for="customPasswordInput" class="form-label text-light">Password</label>
                        <input type="password" class="form-control bg-dark text-light border border-secondary"
                            id="customPasswordInput" name="password">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="showPasswordCheckbox" />
                            <label class="form-check-label text-light" for="showPasswordCheckbox"> Show password
                            </label>
                        </div>
                    </div>

                    <div class="mb-1 col-6">
                        <label for="customRepeatPasswordInput" class="form-label text-light">Repeat password</label>
                        <input type="password" class="form-control bg-dark text-light border border-secondary"
                            id="customRepeatPasswordInput">
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column">
                <div class="d-flex flex-row justify-content-between">
                    <button type="submit" class="btn btn-primary ">Submit</button>
                    <a id="goToButton" href="../index.php" class="btn btn-danger ">Log in</a>
                </div>
            </div>
        </div>
    </form>
    <div class="col-12 position-absolute top-100 waves-container z-2">
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
    <script src="../js/index.js"></script>
    <script src="../js/register.js"></script>
</body>

</html>