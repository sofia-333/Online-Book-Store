<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
session_start();
if (!isset($_SESSION['username'])){
    header("Location: Auth.php");
}
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
if ($count == 1) {
    foreach ($rows as $row) {
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

?>

<!doctype html>
<html lang="en">
<head>
    <title> Bookstore </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"
          crossorigin="anonymous">
    <link rel="stylesheet" href="Styles.css" type="text/css">
    <script src="https://kit.fontawesome.com/403f6cbec6.js" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="wrapper">
    <?php
    $GLOBALS["current_tab"] = 'profile';
    include("Sidebar.php");
    ?>
    <div class="container mt-4 mb-4 p-3 d-flex justify-content-center">
        <div class="card p-4">
            <div class=" image d-flex flex-column justify-content-center align-items-center">
                <i class="fa fa-8x fa-user-circle"></i>
                <span class="name mt-3"><?php echo $first_name . " " . $last_name; ?></span>
                <span class="email"><?php echo $email; ?></span>
                <span class="age"><?php echo "Age - " . $age; ?></span>
                <div class="gap-3 mt-3 icons d-flex flex-row justify-content-center align-items-center"><span><i
                                class="fa fa-twitter mr-2"></i></span> <span><i
                                class="fa fa-facebook-f  mr-2"></i></span> <span><i
                                class="fa fa-instagram  mr-2"></i></span> <span><i
                                class="fa fa-linkedin  mr-2"></i></span></div>
                <div class=" px-2 rounded my-4 date "><span class="join"><?php echo $birth_date; ?></span></div>
                <a href="ProfileForm.php"><i class="fa fa-3x fa-pencil-square-o"></i></a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<style>
    * {
        margin: 0;
        padding: 0
    }

    .card {
        width: 550px;
        background-color: #efefef;
        border: none;
        cursor: pointer;
        transition: all 0.5s;
    }

    .image img {
        transition: all 0.5s
    }

    .name {
        font-size: 22px;
        font-weight: bold
    }

    .email {
        font-size: 14px;
        font-weight: 600
    }

    .age {
        font-size: 18px
    }

    .text span {
        font-size: 13px;
        color: #545454;
        font-weight: 500
    }

    .join {
        font-size: 14px;
        color: #a0a0a0;
        font-weight: bold
    }

    .date {
        background-color: #ccc
    }

</style>