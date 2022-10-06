<?php
$profile = "";
$customers = "";
$orders = "";
$users = "";

if ($GLOBALS["current_tab"] === 'orders')
    $orders = "bg-light text-main";
if ($GLOBALS["current_tab"] === 'profile')
    $profile = "bg-light text-main";
if ($GLOBALS["current_tab"] === 'customers')
    $customers = "bg-light text-main";
if ($GLOBALS["current_tab"] === 'users')
    $users = "bg-light text-main";
?>

<nav id="sidebar">
    <div class="sidebar-header">
        <h3>BookStore</h3>
    </div>

    <ul class="list-unstyled components">
        <li class="<?php echo $profile?>">
            <a href="Profile.php"><i class="fas fa-user-circle mr-3"></i>Profile</a>
        </li>
        <li class="<?php echo $customers?>">
            <a href="Customers.php"><i class="fas fa-server mr-3"></i>Customers</a>
        </li>
        <li class="<?php echo $orders?>">
            <a href="Orders.php"><i class="fas fa-server mr-3"></i>Orders</a>
        </li>
        <li class="<?php echo $users;?>">
            <a href="Users.php"><i class="fas fa-users mr-3"></i>Users</a>
        </li>
        <li>
            <a href='sessionLogout.php'><i class="fa fa-sign-out  mr-3"></i>Logout</a>
        </li>
    </ul>
</nav>