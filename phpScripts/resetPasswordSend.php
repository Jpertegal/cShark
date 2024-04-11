<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once '../phpScripts/dbCon.php';
require '../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete"])) {
        $mail = getMail($_POST["email"]);
        if ($mail) {
            sendForgotPassword($mail);
        }
    }
}
header("Location: ../phpScripts/logout.php");
exit();