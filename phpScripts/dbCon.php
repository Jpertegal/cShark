<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function getDBConnection()
{
    $connString = 'mysql:host=localhost;port=3306;dbname=isitec';
    $user = 'root';
    $pass = '';
    $db = null;
    try {
        $db = new PDO($connString, $user, $pass, [PDO::ATTR_PERSISTENT => true]);
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $db;
    }
}

function logInBD($email, $pass)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "SELECT `idUser`, `username`, `passHash`,`active` FROM `users` WHERE `mail`=:email OR `username`=:user AND active = 1";
    try {
        $usuaris = $conn->prepare($sql);
        $usuaris->execute([':email' => $email, ':user' => $email]);
        if ($usuaris->rowCount() == 1) {
            $dadesUsuari = $usuaris->fetch(PDO::FETCH_ASSOC);
            if (password_verify($pass, $dadesUsuari['passHash'])) {
                $result = ['idUser' => $dadesUsuari['idUser'], 'name' => $dadesUsuari['username']];
            } elseif ($dadesUsuari['active'] == 0) {
                $result = "Mira el teu gmail y verifica la compta.";
            }
        }
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function getMainPhoto($idCurs)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "SELECT * FROM `multimedia` WHERE `idCurs`=:idCurs AND type_multi = 1";
    try {
        $fotos = $conn->prepare($sql);
        $fotos->execute([':idCurs' => $idCurs]);
        if ($fotos->rowCount() == 1) {
            $dadesUsuari = $fotos->fetch(PDO::FETCH_ASSOC);
            $result = $dadesUsuari['path'];
        }
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function getCourses()
{
    $result = false;
    $conn = getDBConnection();
    $sql = "SELECT * FROM `curs`";
    try {
        $cursos = $conn->prepare($sql);
        $cursos->execute();
        $result = $cursos->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function getCourse($idCurs)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "SELECT * FROM `curs` WHERE `idCurs`=:idCurs";
    try {
        $curs = $conn->prepare($sql);
        $curs->execute([':idCurs' => $idCurs]);
        if ($curs->rowCount() == 1) {
            $dadesUsuari = $curs->fetch(PDO::FETCH_ASSOC);
            $result = $dadesUsuari;
        }
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function getPropetari($idCurs)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "SELECT iduser FROM `curs` WHERE `idCurs`=:idCurs";
    try {
        $curs = $conn->prepare($sql);
        $curs->execute([':idCurs' => $idCurs]);
        if ($curs->rowCount() == 1) {
            $dadesUsuari = $curs->fetch(PDO::FETCH_ASSOC);
            $result = $dadesUsuari;
        }
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function sendForgotPassword($email)
{
    $result = false;
    $conn = getDBConnection();
    $valorAleatorioYHash = generarValorAleatorioYHash();
    $valorAleatorio = $valorAleatorioYHash['valor_aleatorio'];
    $hashValorAleatorio = $valorAleatorioYHash['hash_valor_aleatorio'];

    $expiryTime = date('Y-m-d H:i:s', strtotime('+30 minutes'));

    $activationLink = "http://localhost/pages/resetPassword.php?code=$hashValorAleatorio&mail=$email";
    try {
        $sql = "UPDATE `users` SET `resetPassCode` = :resetPassCode, `resetPassExpiry` = :expiryTime WHERE `userName` = :username OR `mail` = :mail";
        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':resetPassCode' => $hashValorAleatorio, ':expiryTime' => $expiryTime, ':username' => $email, ':mail' => $email]);
        $result = $fitxar->rowCount() == 1;
        sendMailPassword($email, $activationLink);
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error en insertar el usuario: " . $e->getMessage() . "</p>";
    } catch (Exception $e) {
        echo "Error al enviar el correo electrónico: {$e->getMessage()}";
    } finally {
        $conn = null;
        return $result;
    }
}

function verificarUsuariBD($email)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "SELECT `idUser` FROM `users` WHERE `mail`=:email OR `username`=:user AND active = 1";
    try {
        $usuaris = $conn->prepare($sql);
        $usuaris->execute([':email' => $email, ':user' => $email]);
        if ($usuaris->rowCount() == 1) {
            $dadesUsuari = $usuaris->fetch(PDO::FETCH_ASSOC);
            $result = true;
        }
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}


function getMail($email)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "SELECT `mail` FROM `users` WHERE `mail`=:email OR `username`=:user";
    try {
        $usuaris = $conn->prepare($sql);
        $usuaris->execute([':email' => $email, ':user' => $email]);
        if ($usuaris->rowCount() == 1) {
            $dadesUsuari = $usuaris->fetch(PDO::FETCH_ASSOC);
            $result = $dadesUsuari['mail'];
        }
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function insertarUsuari($username, $firstName, $lastName, $email, $pass)
{
    $result = false;
    $conn = getDBConnection();
    $valorAleatorioYHash = generarValorAleatorioYHash();
    $valorAleatorio = $valorAleatorioYHash['valor_aleatorio'];
    $hashValorAleatorio = $valorAleatorioYHash['hash_valor_aleatorio'];
    $activationLink = "http://localhost/pages/mailCheckAccount.php?code=$hashValorAleatorio&mail=$email";
    try {
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`username`, `userFirstName`, `userLastName`, `mail`, `passHash`, `creationDate`, `removeDate`, `lastSignIn`, `active`, `activationCode`)
    VALUES (:username, :firstName, :lastName, :email, :pass, NOW(), null, null, 0, :activationCode)";

        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':username' => $username, ':firstName' => $firstName, ':lastName' => $lastName, ':email' => $email, ':pass' => $pass, ':activationCode' => $hashValorAleatorio]);
        $result = $fitxar->rowCount() == 1;
        sendMail($email, $firstName, $lastName, $activationLink);
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error en inserir l'usuari: " . $e->getMessage() . "</p>";
    } catch (Exception $e) {
        echo "Error en enviar el correu electrònic: {$e->getMessage()}";
    } finally {
        $conn = null;
        return $result;
    }
}

function updatePassowrd($email, $pass)
{
    $result = false;
    $conn = getDBConnection();
    try {
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "UPDATE `users` SET `passHash` = :pass WHERE `mail` = :email";

        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':pass' => $pass, ':email' => $email]);
        $result = $fitxar->rowCount() == 1;
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error en inserir l'usuari: " . $e->getMessage() . "</p>";
    } catch (Exception $e) {
        echo "Error en enviar el correu electrònic: {$e->getMessage()}";
    } finally {
        $conn = null;
        return $result;
    }
}

function insertarCurso($idUser, $name, $desc, $photo, $tags)
{
    $result = false;
    $conn = getDBConnection();
    $tagsArray = explode('#', $tags);

    $tagsArray = array_filter($tagsArray);
    $tagsArray = array_map(function ($tag) {
        return '#' . $tag;
    }, $tagsArray);

    $tags = implode(',', $tagsArray);
    try {
        $sql = "INSERT INTO `curs` (`nom`, `desc`, `idUser`, `tags`) VALUES (:name, :desc, :idUser, :tags)";
        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':name' => $name, ':desc' => $desc, ':idUser' => $idUser, ':tags' => $tags]);
        $result = $fitxar->rowCount() == 1;

        $cursoId = $conn->lastInsertId();
        $sql = "INSERT INTO `multimedia` (`type_multi`, `path`, `idCurs`) VALUES ('1', :photo, :cursoId)";
        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':photo' => $photo, ':cursoId' => $cursoId]);
        $result = $fitxar->rowCount() == 1;
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error en inserir l'usuari: " . $e->getMessage() . "</p>";
    } catch (Exception $e) {
        echo "Error en enviar el correu electrònic: {$e->getMessage()}";
    } finally {
        $conn = null;
        return $result;
    }
}

function updateLogin($idUsuari)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "UPDATE users SET lastSignIn = now() WHERE idUser = :id";
    try {
        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':id' => $idUsuari]);
        $result = $fitxar->rowCount() == 1;
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function verifyUser()
{
    $connected = false;
    if (isset($_GET['code']) && isset($_GET['mail'])) {
        $activationCode = $_GET['code'];
        $email = $_GET['mail'];

        try {
            $conn = getDBConnection();

            $sql = "SELECT * FROM users WHERE mail = :email AND activationCode = :activationCode";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':activationCode', $activationCode);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $updateSql = "UPDATE users SET active = 1, activationCode = null, activationDate = NOW() WHERE mail = :email";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bindParam(':email', $email);
                $updateStmt->execute();

                $connected = true;
            } else {
                $connected = false;
            }
        } catch (PDOException $e) {
            echo "Error de la base de dades: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    } else {
        $connected = false;
    }
    return $connected;
}


function verifyResetCode()
{
    $connected = false;
    if (isset($_GET['code']) && isset($_GET['mail'])) {
        $activationCode = $_GET['code'];
        $email = $_GET['mail'];

        try {
            $conn = getDBConnection();

            $sql = "SELECT * FROM users WHERE mail = :email AND resetPassCode = :resetPasscode";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':resetPasscode', $activationCode);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $connected = true;
            } else {
                $connected = false;
            }
        } catch (PDOException $e) {
            echo "Error de la base de dades: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    } else {
        $connected = false;
    }
    return $connected;
}


function sendMail($email, $firstName, $lastName, $activationLink)
{
    $subject = "Registration Confirmation for the C Shark Platform";
    $message = "
    <html>
    <head>
        <title>Welcome to C Shark</title>
    </head>
    <body>
        <p>Thank you for registering with our platform. We welcome you aboard.</p>
        <p>Click on the following link to activate your account:</p>
        <p><a href=\"$activationLink\">Active your account now!</a></p>
        <img src='http://localhost/imgs/logo.png' alt='Corporate Image Wopepera'>
    </body>
    </html>";

    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->CharSet = 'UTF-8';
    $mail->Username = 'wopepera@gmail.com';
    $mail->Password = 'vufh kmct grkb pikv';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom($mail->Username, 'Cshark inc.');
    $mail->addAddress($email, $firstName . ' ' . $lastName);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;

    $mail->send();
}

function sendMailPassword($email, $activationLink)
{
    $subject = "Registration Confirmation for the C Shark Platform";
    $message = "
<html>
<head>
    <title>Password Reset - C Shark</title>
</head>
<body>
    <p>Hello,</p>
    <p>We received a request to reset the password for your account at C Shark. Please follow the link below to complete the process:</p>
    <p><a href=\"$activationLink\">Reset your password now</a></p>
    <p>If you didn't request this change, please ignore this message.</p>
    <img src='http://localhost/imgs/logo.png' alt='Corporate Image Wopepera'>
</body>
</html>";


    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->CharSet = 'UTF-8';
    $mail->Username = 'wopepera@gmail.com';
    $mail->Password = 'vufh kmct grkb pikv';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom($mail->Username, 'Cshark inc.');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;

    $mail->send();
}

function generarValorAleatorioYHash()
{
    $valorAleatorio = bin2hex(random_bytes(32));
    $hashValorAleatorio = hash('sha256', $valorAleatorio);

    return array(
        'valor_aleatorio' => $valorAleatorio,
        'hash_valor_aleatorio' => $hashValorAleatorio,
    );
}

function printarCursSenseEditar($curs)
{
    $videos = getVideos($curs['idCurs']);
    echo '
    <main class="col-12 position-absolute start-50 w-100 rounded z-2 d-flex flex-column align-items-center">
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

        <div class="dont_show fish-container w-100 z-3">
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
            <div class="d-flex flex-row justify-content-between flex-wrap">

                <div class="col-12 col-md-6">
                    <img class=" w-100  rounded main_photo" src="../imgs/uploads/' . getMainPhoto($curs['idCurs']) . '" alt="pirate" data-bs-toggle="tooltip" data-bs-title="See profile" />
                    <div class="buttons">
                    <form method="POST" class="w-100">
                        <input name="like" type="hidden" value="1"></input>
                        <button class="btn btn-primary">
                            <img width="20" height="20" src="https://img.icons8.com/material-rounded/24/facebook-like--v1.png" alt="facebook-like--v1"/>
                            <p >' . $curs['likes'] . '</p>
                        </button>
                    </form>
                    <form method="POST" class="w-100">
                        <input name="like" type="hidden" value="-1"></input>
                        <button class="btn btn-danger me">
                            <img width="20" height="20" src="https://img.icons8.com/material-rounded/24/thumbs-down.png" alt="thumbs-down"/>
                            <p >' . $curs['dislikes'] . '</p>
                        </button>
                    </form>
                    </div>
                </div>
                <div class="col-12 col-md-5">
                <h1 id="title">Curso</h1>
                <p >' . $curs['nom'] . '</p>
                <h1>Descripción</h1>
                <p >' . $curs['desc'] . '</p>
                </div>
                <hr/>
                </div>';
    if ($videos != false) {
        for ($i = 0; $i < count($videos); $i++) {

            echo '<video class="w-100 h-100 mt-3" controls>
                                <source src="../imgs/uploads/' . $videos[$i]['path'] . '">
                            </video>';
        }
    }

    echo '
                    </div>
                </div>
            </main>';
}

function printarCursAmbForm($curs)
{
    echo '<h1 id="title" class="position-fixed text-light start-50 translate-middle-x">' . $curs['nom'] . '</h1>
    <main class="col-12 position-absolute start-50 w-100 rounded z-2 d-flex flex-column align-items-center">
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

        <div class="dont_show fish-container w-100 z-3">
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
            <form class="d-flex flex-row justify-content-between flex-wrap" method="POST" enctype="multipart/form-data">
                <div class="col-12 col-md-6">
                    <img class=" w-100  rounded main_photo" src="../imgs/uploads/' . getMainPhoto($curs['idCurs']) . '" alt="pirate" data-bs-toggle="tooltip" data-bs-title="See profile" />
                    <div class="fileInput">
                        <label for="formFile" class="form-label">Cambiar imagen</label>
                        <input class="form-control" name="imagen" type="file" id="formFile">
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <h1>Nombre</h1>
                    <input type="text" name="nom" class="form-control" value="' . $curs['nom'] . '"></input>
                    <h1>Descripción</h1>
                    <textarea name="desc" class="w-100 h-50 form-control">' . $curs['desc'] . '</textarea>
                    <button class="btn btn-primary mt-3">Submit</button>
                </div>
            </form>
            <form enctype="multipart/form-data" method="POST">
                <label for="formFile" class="form-label">Subir videos</label>
                <input class="form-control" name="video" type="file" id="formFile" multiple>
                <button class="btn btn-primary mt-3">Submit</button>
            </form>
            <form method="POST" action="../phpScripts/deleteCourse.php">
                <input class="form-control" name="delete" type="hidden" value="' . $curs['idCurs'] . '">
                <button class="btn btn-danger mt-3">Delete course</button>
            </form>
        </div>
    </div>
    </main>';
}

function deleteCourse($id)
{
    try {
        $conn = getDBConnection();

        $sql = "DELETE FROM curs WHERE idCurs = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error de la base de dades: " . $e->getMessage();
    } finally {
        $conn = null;
    }
}

function updateCourse($name, $desc, $file, $idcurs)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "UPDATE curs SET nom = :name, `desc` = :desc WHERE idCurs = :id";
    try {
        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':name' => $name, ':desc' => $desc, ':id' => $idcurs]);
        if ($file['name']) {
            updatePhoto($file, $idcurs);
            $uploaddir = '../imgs/uploads/';
            $uploadfile = $uploaddir . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $uploadfile);
        }
        $result = $fitxar->rowCount() == 1;
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function updatePhoto($file, $idcurs)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "UPDATE multimedia SET path = :path WHERE idCurs = :id AND type_multi = 1";
    try {
        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':path' => $file['name'], ':id' => $idcurs]);
        $result = $fitxar->rowCount() == 1;
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function insertVideo($file, $idcurs)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "INSERT INTO `multimedia` (`type_multi`, `path`, `idCurs`) VALUES ('2', :file, :cursoId)";
    try {
        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':file' => $file['name'], ':cursoId' => $idcurs]);
        if ($file['name']) {
            $uploaddir = '../imgs/uploads/';
            $uploadfile = $uploaddir . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $uploadfile);
        }
        $result = $fitxar->rowCount() == 1;
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function getVideos($idCurs)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "SELECT * FROM `multimedia` WHERE `idCurs`=:idCurs and `type_multi` = 2";
    try {
        $curs = $conn->prepare($sql);
        $curs->execute([':idCurs' => $idCurs]);
        if ($curs->rowCount() >= 1) {
            $result = $curs->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function alreadyLike($idUser, $idCurs)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "SELECT `like` FROM `usuaripuntuaciocurs` WHERE `idCurs`=:idCurs and `idUsuari` = :idUser";
    try {
        $curs = $conn->prepare($sql);
        $curs->execute([':idCurs' => $idCurs, ':idUser' => $idUser]);
        if ($curs->rowCount() >= 1) {
            $result = $curs->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function insertarLike($idUser, $idCurs, $like)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "INSERT INTO `usuaripuntuaciocurs` (idCurs, idUsuari, `like`) VALUES (:idCurs, :idUser,  :like)";
    try {
        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':idCurs' => $idCurs, ':idUser' => $idUser, ':like' => $like]);
        $result = $fitxar->rowCount() == 1;
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function updateLike($idUser, $idCurs, $like)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "UPDATE `usuaripuntuaciocurs` SET `like` = :like WHERE idCurs = :idCurs AND idUsuari = :idUser";
    try {
        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':like' => $like, ':idCurs' => $idCurs, ':idUser' => $idUser]);
        $result = $fitxar->rowCount() == 1;
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function updateLikesInCurs($idCurs, $likes)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "UPDATE `curs` SET `likes` = :like + `likes` WHERE idCurs = :idCurs";
    try {
        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':like' => $likes, ':idCurs' => $idCurs]);
        $result = $fitxar->rowCount() == 1;
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}

function updateDisLikesInCurs($idCurs, $dislikes)
{
    $result = false;
    $conn = getDBConnection();
    $sql = "UPDATE `curs` SET `dislikes` = :dislike + `dislikes` WHERE idCurs = :idCurs";
    try {
        $fitxar = $conn->prepare($sql);
        $fitxar->execute([':dislike' => $dislikes, ':idCurs' => $idCurs]);
        $result = $fitxar->rowCount() == 1;
    } catch (PDOException $e) {
        echo "<p style=\"color:red;\">Error " . $e->getMessage() . "</p>";
    } finally {
        return $result;
    }
}
