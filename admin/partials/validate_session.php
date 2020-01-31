<?php

session_start();
require_once __DIR__."/../../vendor/autoload.php";

/**
 * Created by PhpStorm.
 * User: baran
 * Date: 1/30/20
 * Time: 3:10 PM
 */

// Initialize the session


// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true){

}else{
    header("location: ".MY_HOST."login.php");
    exit;
}