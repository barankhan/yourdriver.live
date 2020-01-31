<?php
require_once __DIR__."/vendor/autoload.php";
// Include config file

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){



    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }


    // Validate credentials
    if(empty($username_err) && empty($password_err)){

       $userObj =  new User();
       $userObj->getAdminWithMobileAndPassword($username,$password);

       if($userObj->getId()!=0){
           session_start();

           // Store data in session variables
           $_SESSION["logged_in"] = true;
           $_SESSION["id"] = $id;
           $_SESSION["name"] = $userObj->getName();

           header("location: ".MY_HOST."admin/index.php");

       } else{
        // Display an error message if password is not valid
        $password_err = "The password you entered was not valid.";
        }

    }

}
?>

    <!DOCTYPE html>
    <html>
<head>
    <link rel="stylesheet" href="<?php echo MY_HOST;?>/bootstrap/css/bootstrap.min.css">

</head>
<body>
<div class="container">
<div class="wrapper">
    <?php if(!empty($username_err) || !empty($password_err)){ ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $username_err ?>

        <?php echo $password_err ?>
    </div>
    <?php }?>
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Mobile</label>
            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
    </form>
</div>
</div>
</body>
