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
        <h3><a href="/admin/">Driver Admin Panel</a></h3>
    </div>

    <ul class="list-unstyled components">
        <p>Welcome <?php echo $_SESSION['name']; ?></p>
        <li class="active">
            <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">User</a>
            <ul class="list-unstyled" id="homeSubmenu">
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
                <li>
                    <a href="<?php echo MY_HOST ?>admin/users/list_drivers.php">Drivers</a>
                </li>
                <li>
                    <a href="<?php echo MY_HOST ?>admin/users/driver_online_hours.php">Online Time sheet</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#autoridesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Auto Rides</a>
            <ul class="list-unstyled" id="autoridesSubmenu">
                <li>
                    <a href="<?php echo MY_HOST ?>admin/rides/unattended_rides.php?vehicle_type=Auto">Unattended</a>
                </li>



                <li>
                    <a href="<?php echo MY_HOST ?>admin/rides/passenger_cancelled_rides.php?vehicle_type=Auto">Passenger Cancelled</a>
                </li>


                <li>
                    <a href="<?php echo MY_HOST ?>admin/rides/driver_cancelled_rides.php?vehicle_type=Auto">Driver Cancelled</a>
                </li>


                <li>
                    <a href="<?php echo MY_HOST ?>admin/rides/completed_rides.php?vehicle_type=Auto">Completed</a>
                </li>



            </ul>
        </li>
        <li>
            <a href="#bikeridesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Bike Rides</a>
            <ul class="list-unstyled" id="bikeridesSubmenu">
                <li>
                    <a href="<?php echo MY_HOST ?>admin/rides/unattended_rides.php?vehicle_type=Bike">Unattended </a>
                </li>
                <li>
                    <a href="<?php echo MY_HOST ?>admin/rides/passenger_cancelled_rides.php?vehicle_type=Bike">Passenger Cancelled </a>
                </li>
                <li>
                    <a href="<?php echo MY_HOST ?>admin/rides/driver_cancelled_rides.php?vehicle_type=Bike">Driver Cancelled </a>
                </li>
                <li>
                    <a href="<?php echo MY_HOST ?>admin/rides/completed_rides.php?vehicle_type=Bike">Completed </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#carridesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Car Rides</a>
            <ul class="list-unstyled" id="carridesSubmenu">
                <li>
                    <a href="<?php echo MY_HOST ?>admin/rides/unattended_rides.php?vehicle_type=Car">Unattended </a>
                </li>
                <li>
                    <a href="<?php echo MY_HOST ?>admin/rides/passenger_cancelled_rides.php?vehicle_type=Car">Passenger Cancelled </a>
                </li>
                <li>
                    <a href="<?php echo MY_HOST ?>admin/rides/driver_cancelled_rides.php?vehicle_type=Car">Driver Cancelled </a>
                </li>
                <li>
                    <a href="<?php echo MY_HOST ?>admin/rides/completed_rides.php?vehicle_type=Car">Completed </a>
                </li>

            </ul>
        </li>
        <li>
            <a href="<?php echo MY_HOST ?>admin/support_tickets/tickets.php">Support Tickets</a>
        </li>

        <li>
            <a href="#SMSQueue" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">SMS QUEUE</a>
            <ul class="collapse list-unstyled" id="SMSQueue">
                <li>
                    <a href="<?php echo MY_HOST ?>admin/sms/populate_sms_queue.php">Populate</a>
                </li>

            </ul>
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