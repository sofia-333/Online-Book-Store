<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: Auth.php");
}
$phone_error = "";
$email_error = "";
$last_name_error = "";
$first_name_error = "";
if (isset($_POST["submit"])) {
    $id = $_POST["id"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $pattern = "/^[0-9]*$/i";
    if ($_POST["phone"] and !preg_match("$pattern", $_POST["phone"])) {
        $phone_error = 'Please use valid only numbers for phone field.';
    } else if (!$_POST["phone"]) {
        $phone_error = 'Phone field is required';
    }
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
    if (!$first_name_error and !$last_name_error and !$email_error and !$phone_error) {
        $dsn = "mysql:dbname=university";
        $user = "root";
        $pass = "12131415";
        try {
            $conn = new PDO($dsn, $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if (isset($id) && $id !== "") {
                $sql = "UPDATE customers SET first_name='$first_name', last_name='$last_name', email='$email', phone='$phone', address='$address' WHERE id='$id' ";
                $response = $conn->prepare($sql)->execute();
            } else {
                $sql = "INSERT INTO customers (first_name, last_name, email, phone, address) VALUES ('$first_name', '$last_name', '$email', '$phone', '$address')";
                $response = $conn->prepare($sql)->execute();
            }
            if ($response === TRUE) {
                if (isset($id) && $id !== "") {
                    echo '<script>alert("Customer updated successfully")</script>';
                } else {
                    echo '<script>alert("Customer created successfully")</script>';
                }
                header("Location: http://" . $_SERVER['HTTP_HOST'] . "/study/Project/Customers.php", true, 301);
                die();
            }
        } catch (Exception $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
        }
    }
} else {
    $dsn = "mysql:dbname=university";
    $user = "root";
    $pass = "12131415";
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $first_name = "";
    $last_name = "";
    $address = "";
    $email = "";
    $phone = "";
    $id = null;
    if (isset($_GET['id'])) {
        $customer_id = $_GET['id'];
        $sql = "SELECT * FROM customers WHERE id='$customer_id'";
        $rows = $conn->query($sql);
        $count = $rows->rowCount();
        if ($count == 1) {
            foreach ($rows as $row) {
                $id = $row["id"];
                $first_name = $row["first_name"];
                $last_name = $row["last_name"];
                $address = $row["address"];
                $email = $row["email"];
                $phone = $row["phone"];
            }
        } else {
            echo "Could not find customer info";
        }
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
        <?php
        if (isset($id)) {
            echo '<h1>Update Customer</h1>';
        } else {
            echo '<h1>Create Customer</h1>';
        }
        ?>
        <br><br>
        <div class="row">
            <div class="col-md-4">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input class="form-control" type="text" name="first_name" value="<?= $first_name ?>">
                        <?php echo "<span class='error-message'>$first_name_error</span>"; ?>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input class="form-control" type="text" name="last_name" value="<?= $last_name ?>">
                        <?php echo "<span class='error-message'>$last_name_error</span>"; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="email" name="email" value="<?= $email ?>">
                        <?php echo "<span class='error-message'>$email_error</span>"; ?>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input class="form-control" type="text" name="phone" value="<?= $phone ?>">
                        <?php echo "<span class='error-message'>$phone_error</span>"; ?>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input class="form-control" name="address" value="<?= $address ?>">
                    </div>
                    <input type='hidden' name='id' value="<?= $id ?>">
                    <button type="submit" class="btn btn bg-main" name="submit">Submit</button>
                    <a href="Customers.php">
                        <button type="button" class="btn bg-main" id="btnBack">Cancel</button>
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
