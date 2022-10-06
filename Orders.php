<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require("ObjectsList.php");
require("Page.php");
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: Auth.php");
}
$dsn = "mysql:dbname=university";
$user = "root";
$pass = "12131415";
$dbConn = new PDO($dsn, $user, $pass);
$booksPerPage = 5;
$booksPerPageArray = array(5, 10, 15, 20, 50);
$query = "Select orders.id , customers.id as customer_id, books.id as books_id, books.author, books.title, books.publisher, books.ISBN, orders.status, orders.customer_id, customers.first_name, customers.email, customers.last_name from orders  JOIN customers on orders.customer_id=customers.id JOIN books on orders.book_id=books.id";
if (isset($_POST['delete'])) {
    $delete_id = $_POST['id'];
    $sql = "DELETE FROM orders WHERE id='$delete_id';";
    $response = $dbConn->prepare($sql)->execute();
}
if (isset($_POST['reset'])) {
    if (isset($_POST['bookPage'])) {
        $_SESSION['bookPage'] = $_POST['bookPage'];
        $booksPerPage = (int)$_SESSION['bookPage'];
        $page = 1;
    }
} else {
    $booksPerPage = $_SESSION['bookPage'] ?? $booksPerPage;
    $page = $_GET['page'] ?? 1;
}
$objectsList = new ObjectsList($dbConn, "orders", $query);
$bookPage = new Page($objectsList, $page, $booksPerPage);
$total = $objectsList->count();
$first = $bookPage->getFirstIndex() + 1;
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
</head>
<body>
<div class="wrapper">
    <?php
    $GLOBALS["current_tab"] = 'orders';
    include("Sidebar.php");
    ?>
    <div class="container mt-5">
        <div>
            <form action="" method="post" class="d-flex p-2 gap-3">
                <select class="form-select" style="width: 6%" data-style="bg-main" name="bookPage">
                    <?php
                    foreach ($booksPerPageArray as $i) {
                        $isSelected = $i == $_SESSION['bookPage'] ? ' selected="selected"' : '';
                        echo "<option value=\"" . $i . "\"" . $isSelected . ">" . $i . "</option>";
                    }
                    ?>
                </select>
                <button type="submit" name="reset" class="btn bg-main ml-3">Reset</button>
                <a class="align-items-right m-0 ml-auto p-1" href="OrderForm.php"><i
                            class="fa fa-2x fa-plus-square m-0" aria-hidden="true"></i></a>
            </form>
            <div>
                <?php echo "{$first}-{$bookPage->getLastIndex()} of $total" ?>
            </div>

        </div>
        <div class="table-container table-responsive">
            <table class="table table-bordered table-responsive-md  my-4 ">
                <tr>
                    <th>ISBN</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Status</th>
                    <th>Actions</th>

                </tr>
                <tr> <?php
                    foreach ($bookPage as $key => $order) {
                        if ($order) {
                            echo "<tr><td>{$order['ISBN']}</td>
                              <td>{$order['title']}</td>
                              <td>{$order['author']}</td>
                              <td>{$order['publisher']}</td>
                              <td>{$order['first_name']} {$order['last_name']}</td>
                              <td>{$order['email']}</td>
                              <td>{$order['status']}</td>
                              <td>
                              <div  class='d-flex'>
                                  <a href='OrderForm.php?id={$order['id']}' class='justify-content-center mr-3'><i class='fa fa-pencil-square-o'></i></a>
                                  <form action='Orders.php' method='post'>
                                  <input type='hidden' name='id' value='{$order['id']}'/>
                                  <input type='submit' name='delete' value='x' class='px-2' style='cursor: pointer'/>
                                  </form>
                              </div>
                              </td>
                          </tr>";
                        }

                    } ?> </tr>

            </table>
        </div>
        <nav aria-label="...">
            <ul class="pagination">
                <?php
                $totalPages = ceil($total / $booksPerPage);
                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i == $page)
                        echo "<li class='page-item active'><a class='page-link'>$i</a></li>";
                    else echo "<li class='page-item bg-main'><a class='page-link' href='?page=$i'>$i</a></li>";
                }
                ?>
            </ul>
        </nav>
    </div>
</body>