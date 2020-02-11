<?php
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/30/20
 * Time: 3:37 PM
 */
?>



<nav id="sidebar">
    <div class="sidebar-header">
        <h3>Driver Admin Panel</h3>
    </div>

    <ul class="list-unstyled components">
        <p>Welcome <?php echo $_SESSION['name']; ?></p>
        <li class="active">
            <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">User</a>
            <ul class="collapse list-unstyled" id="homeSubmenu">
                <li>
                    <a href="<?php echo MY_HOST ?>admin/users/list_driver_approval.php">Driver Approvals</a>
                </li>
                <li>
                    <a href="<?php echo MY_HOST ?>admin/users/list_not_verified_users.php">Not Verified Users</a>
                </li>
                <li>
                    <a href="<?php echo MY_HOST ?>admin/users/list_users.php">Users</a>
                </li>
                <li>
                    <a href="<?php echo MY_HOST ?>admin/users/online_drivers.php">Online Drivers</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">About</a>
        </li>
        <li>
            <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Pages</a>
            <ul class="collapse list-unstyled" id="pageSubmenu">
                <li>
                    <a href="#">Page 1</a>
                </li>
                <li>
                    <a href="#">Page 2</a>
                </li>
                <li>
                    <a href="#">Page 3</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#">Portfolio</a>
        </li>
        <li>
            <a href="#">Contact</a>
        </li>
    </ul>
</nav>