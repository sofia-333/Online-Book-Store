<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
session_start();
$dsn = "mysql:dbname=university";
$user = "root";
$pass = "12131415";
$conn = new PDO($dsn, $user, $pass);
if (!isset($_SESSION['username'])) {
    header("Location: Auth.php");
}
if (isset($_POST["submit"])) {
    $id = $_POST["id"];
//    $ISBN = $_POST["ISBN"];
//    $author = $_POST["author"];
//    $title = $_POST["title"];
//    $publisher = $_POST["publisher"];
    $book_id = $_POST["book_id"];
    $customer_id = $_POST["customer_id"];
    $status = $_POST["status"];
    $pattern = "/^[0-9]*$/i";
    try {
        if (isset($id) && $id !== "") {
            $sql = "UPDATE orders SET book_id='$book_id', customer_id='$customer_id', status='$status' WHERE id='$id'";
            $response = $conn->prepare($sql)->execute();
        } else {
            $sql = "INSERT INTO orders (book_id, customer_id, status) VALUES ( '$book_id', '$customer_id', '$status')";
            $response = $conn->prepare($sql)->execute();
        }
        if ($response === TRUE) {
            if (isset($id) && $id !== "") {
                echo '<script>alert("Order updated successfully")</script>';
            } else {
                echo '<script>alert("Order created successfully")</script>';
            }
            header("Location: http://" . $_SERVER['HTTP_HOST'] . "/study/Project/Orders.php", true, 301);
            die();
        }
    } catch (Exception $e) {
        echo 'Exception -> ';
        var_dump($e->getMessage());
    }

} else {
    $book_id = "";
    $customer_id = "";
    $status = "";
    $id = "";
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM orders WHERE id='$id'";
        $orders = $conn->query($sql);
        $count = $orders->rowCount();
        if ($count == 1) {
            foreach ($orders as $order) {
                $book_id = $order['book_id'];
                $customer_id = $order['customer_id'];
                $status = $order['status'];
                $id = $order['id'];
            }
        } else {
            echo "Could not find order info";
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
        if (isset($id) && $id != "") {
            echo '<h1>Update Order</h1>';
        } else {
            echo '<h1>Create Order</h1>';
        }
        ?>
        <br><br>
        <div class="row">
            <div class="col-md-4">
                <form method="post" action="">
                    <div>
                        <label for="book">Book</label>
                        <select class="form-select mb-3" name="book_id" style="width: 100%">
                            <?php
                            $sql = "SELECT * from books";
                            $books = $conn->query($sql);
                            foreach ($books as $book) {
                                $value = $book['title'] . " - " . $book['author'] . " - " . $book['publisher'];
                                $isSelected = $book['id'] == $book_id ? ' selected="selected"' : '';
                                echo "<option value=\"" . $book['id'] . "\"" . $isSelected . ">" . $value . "</option>";

                            }
                            ?>
                            <!--                            <option value="" selected>Select book</option>-->
                        </select>
                    </div>
                    <div>
                        <label for="customer">Customer</label>
                        <select class="form-select mb-3" name='customer_id' style="width: 100%">
                            <?php
                            $sql = "SELECT * from customers";
                            $customers = $conn->query($sql);
                            foreach ($customers as $customer) {
                                $value = $customer['first_name'] . " " . $customer['last_name'] . " - " . $customer['email'];
                                $isSelected = $customer['id'] == $customer_id ? ' selected="selected"' : '';
                                echo "<option value=\"" . $customer['id'] . "\"" . $isSelected . ">" . $value . "</option>";

                            }
                            ?>
                            <!--                            <option value="" selected>Select customer</option>-->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-select mb-3" style="width: 100%" name="status">
                            <option value="cancelled" <?php if ($status === 'cancelled') echo 'selected'; ?>
                            >Cancelled</option>
                            <option value="done" <?php if ($status === 'done') echo 'selected'; ?>>Done</option>
                            <option value="pending" <?php if ($status === 'pending') echo 'selected'; ?>
                            >Pending</option>
                        </select>
                    </div>
                    <input type='hidden' name='id' value="<?= $id ?>">
                    <button type="submit" class="btn btn bg-main" name="submit">Submit</button>
                    <a href="Orders.php">
                        <button type="button" class="btn bg-main" id="btnBack">Cancel</button>
                    </a>
                </form>
            </div>
        </div>
    </div>

</div>