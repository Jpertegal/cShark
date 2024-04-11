<?php
require_once '../phpScripts/dbCon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete"])) {
        deleteCourse($_POST["delete"]);
    }
}
header("Location: ../pages/home.php");
exit();