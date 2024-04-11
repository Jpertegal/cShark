<?php
require '../phpScripts/dbCon.php';
session_start();
$cursos = null;
if (!isset($_SESSION['user'])) {
    header("Location: ../phpScripts/logout.php");
    exit();
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (count($_POST) == 3) {
            if (isset($_POST['name']) && isset($_POST['desc']) && isset($_POST['tags']) && isset($_FILES["file"])) {
                insertarCurso($_SESSION['id'], $_POST['name'], $_POST['desc'], $_FILES["file"]["name"], $_POST['tags']);
                $uploaddir = '../imgs/uploads/';
                $uploadfile = $uploaddir . basename($_FILES['file']['name']);
                move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);
            }
        }
    }
    $cursos = getCourses();
}
?>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/home.css">
    <title>Main Page</title>
</head>

<body id="body" class=" overflow-x-hidden">
    <header class="h-25">
        <div class="position-fixed start-0 top-0 bg-light h-100 p-1 z-3">

            <button class="btn btn-light d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#exampleModal">
                <img width="20" height="20" src="https://img.icons8.com/ios-glyphs/30/macos-maximize.png"
                    alt="macos-maximize" data-bs-toggle="tooltip" data-bs-title="Add course" /> </button>

            <a class="log_out btn btn-light d-flex align-items-center position-relative top-50"
                href="../phpScripts/logout.php">
                <img width="20" height="20" src="https://img.icons8.com/ios-glyphs/30/viking-ship.png" alt="viking-ship"
                    data-bs-toggle="tooltip" data-bs-title="Log out" />
            </a>

        </div>
    </header>
    <h1 class=" position-fixed top-50 start-50 translate-middle text-light">Welcome to the home</h1>
    <main class="col-12 position-absolute top-50 start-50 w-100 rounded z-2 d-flex flex-column align-items-center">
        <div id="waves" class="col-12 position-absolute waves-container z-1 w-100 top-0">
            <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                <defs>
                    <path id="gentle-wave"
                        d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
                </defs>
                <g class="parallax">
                    <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(0, 26, 51, 0.7)" />
                    <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(0, 26, 51, 0.5)" />
                    <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(0, 26, 51, 0.3)" />
                    <use xlink:href="#gentle-wave" x="48" y="7" fill="rgba(0, 26, 51, 1)" />
                </g>
            </svg>
        </div>

        <div class="fish-container w-100 z-3">
            <div class="fish ">
                <div class="fish-body">
                    <div class="eye">
                        <div class="pupil"></div>
                    </div>
                </div>
                <div class="fin"></div>
                <div class="fin fin-bottom"></div>
            </div>
        </div>
        <div class="bg-light w-75 rounded z-3 p-5">
            <div class="d-flex flex-row justify-content-between">
                <h2>Courses <img width="20" height="20" src="https://img.icons8.com/ios-glyphs/30/pirate.png"
                        alt="pirate" data-bs-toggle="tooltip" data-bs-title="See profile" /></h2>
                <a href="" class="btn btn-light h-50">More</a>
            </div>
            <div id="your_courses" class="d-flex flex-row flex-wrap justify-content-between mt-3 mb-3">
                <?php
                foreach ($cursos as $course) {
                    echo '
    <div class="card col-12 col-lg-4 mb-3" style="width: 18rem;">
        <img src="../imgs/uploads/' . getMainPhoto($course['idCurs']) . '" class="card-img-top img-fluid" style="height: 200px; object-fit: cover;" alt="Course Image">
        <div class="card-body">
            <h5 class="card-title">' . $course['nom'] . '</h5>
            <p class="desc card-text overflow-y-scroll">' . $course['desc'] . '</p>
            <a href="course.php?idCurs=' . $course['idCurs'] . '" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>';
                }
                ?>


            </div>
    </main>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add new course</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="customEmailInput" class="form-label text-light">Name of the course</label>
                            <input type="text" class="form-control bg-dark text-light border border-secondary"
                                id="customEmailInput" aria-describedby="emailHelp" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Description of the course</label>
                            <textarea name="desc" class="form-control bg-dark text-light border border-secondary" id=""
                                rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Main photo</label>
                            <input name="file" class="form-control bg-dark text-light border border-secondary"
                                type="file" id="formFile" accept=".png, .jpg, .jpeg, .webp">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Description of the course</label>
                            <textarea class="form-control bg-dark text-light border border-secondary" name="tags" id=""
                                rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add course</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="../js/index.js"></script>
</body>

</html>