<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: Auth.php");
}
$email_error = "";
$username_error = "";
$last_name_error = "";
$first_name_error = "";
if (!isset($_SESSION['username'])) {
    header("Location: Auth.php");
}
if (isset($_POST["submit"])) {
    $id = $_POST["id"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $age = $_POST["age"];
    $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
    if ($email and !preg_match($pattern, $email)) {
        $email_error = 'Please use valid email for email field.';
    } else if (!$email) {
        $email_error = 'Email field is required';
    }
    $pattern = "/^[A-Za-z]+$/";
    if ($first_name and !preg_match($pattern, $first_name)) {
        $first_name_error = 'Please use valid first name. Only (A-Z) (a-z) and space are allowed.';
    } else if (!$first_name) {
        $first_name_error = 'First Name field is required';
    }
    if ($last_name and !preg_match($pattern, $last_name)) {
        $last_name_error = 'Please use valid last name. Only (A-Z) (a-z) and space are allowed.';
    } else if (!$last_name) {
        $last_name_error = 'Last Name field is required';
    }
    if (!$first_name_error and !$last_name_error and !$email_error) {
        $dsn = "mysql:dbname=university";
        $user = "root";
        $pass = "12131415";
        try {
            $conn = new PDO($dsn, $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email', username='$username', age='$age' WHERE id='$id' ";
            $response = $conn->prepare($sql)->execute();
            if ($response === TRUE) {
                echo '<script>alert("Profile updated successfully")</script>';
                header("Location: http://" . $_SERVER['HTTP_HOST'] . "/study/Project/Profile.php", true, 301);
                die();
            }
        } catch (Exception $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
        }
    }
} else {
    $logged_user = $_SESSION['username'];
    $dsn = "mysql:dbname=university";
    $user = "root";
    $pass = "12131415";
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM users WHERE username='$logged_user'";
    $rows = $conn->query($sql);
    $count = $rows->rowCount();
    $username = "";
    $first_name = "";
    $last_name = "";
    $birth_date = "";
    $age = "";
    $email = "";
    $id = "";
    if ($count == 1) {
        foreach ($rows as $row) {
            $id = $row["id"];
            $username = $row["username"];
            $first_name = $row["first_name"];
            $last_name = $row["last_name"];
            $birth_date = $row["birth_date"];
            $age = $row["age"];
            $email = $row["email"];
        }
    } else {
        echo "Could not find user info";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title> Bookstore </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="Styles.css" type="text/css">
</head>

<body>
<div class="wrapper">
    <?php
    $GLOBALS["current_tab"] = '';
    include("Sidebar.php");
    ?>
    <div class="container">
        <br><br>
        <h1>Update Profile</h1>
        <br><br>
        <div class="row">
            <div class="col-md-4">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input class="form-control" type="text" name="first_name" value="<?= $first_name ?>">
                        <?php echo "<div class='error-message'>$first_name_error</div>"; ?>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input class="form-control" type="text" name="last_name" value="<?= $last_name ?>"">
                        <?php echo "<span class='error-message'>$last_name_error</span>"; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Username</label>
                        <input class="form-control" type="text" name="username" value="<?= $username ?>">
                        <?php echo "<span class='error-message'>$username_error</span>"; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="text" name="email" value="<?= $email ?>">
                        <?php echo "<span class='error-message'>$email_error</span>"; ?>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Age</label>
                        <input class="form-control" type="number" name="age" value="<?= (int)$age ?>">
                    </div>
                    <input type='hidden' name='id' value="<?= $id ?>">
                    <button type="submit" class="btn btn bg-main" name="submit">Submit</button>
                    <a href="Profile.php">
                        <button type="button" class="btn bg-main" id="btnBack">Cancel</button>
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
<style>

</style>
