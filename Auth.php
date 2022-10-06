<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
session_start();
$_SESSION = array();
$error = '';
if (isset($_SESSION['username'])) {
    header("Location: http://" . $_SERVER['HTTP_HOST'] . "Profile.php");
    die();
} else {
    if (isset($_POST['username'])) {
        $typed_username = $_POST['username'];
        $typed_password = $_POST['password'];
        $dsn = "mysql:dbname=university";
        $user = "root";
        $pass = "12131415";
        $conn = new PDO($dsn, $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM users WHERE username='$typed_username' and password='$typed_password'";
        $rows = $conn->query($sql);
        $count = $rows->rowCount();
        if ($count == 1) {
            $_SESSION['username'] = $_POST['username'];
            foreach ($rows as $row) {
                $_SESSION['first_name'] = $row['first_name'];
                $_SESSION['last_name'] = $row['last_name'];
            }
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/study/Project/Profile.php", true, 301);
            die();
        } else {
            $error = "Wrong username or password.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <title> Bookstore </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"
          crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex flex-column justify-content-center align-items-center">
        <div class="card p-4 text-light bg-dark mb-5">
            <div class="card-header">
                <h3>Sign In</h3>
            </div>
            <div class="card-body">
                <form name="login" action="" method="post">
                    <div class="input-group form-group mt-3">
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-row">
                                <div class="bg-secondary rounded-start">
                                    <span class="m-3"><i class="fas fa-user mt-2"></i></span>
                                </div>
                                <input type="text" class="form-control"
                                       placeholder="username" name="username">
                            </div>
                            <small id="usernameError" class="form-text text-danger"><?= $error ?></small>
                        </div>
                    </div>
                    <div class="input-group form-group mt-3 ">
                        <div class="bg-secondary rounded-start">
                            <span class="m-3"><i class="fas fa-key mt-2"></i></span>
                        </div>
                        <input type="password" class="form-control"
                               placeholder="password" name="password">
                    </div>

                    <div class="form-group mt-3">
                        <input type="submit" value="Login"
                               class="btn bg-secondary float-end text-white w-100"
                               name="login-btn">
                    </div>
                </form>
            </div>
        </div>
    </div>


</body>
</html>
