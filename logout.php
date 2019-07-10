<?php
session_start();
if (!isset($_SESSION['customers'])) {
 header( "Location: index.php");
} else if(isset($_SESSION[ 'customers'])!="") {
 header("Location: home.php");
}

if  (isset($_GET['logout'])) {
 unset($_SESSION['customers' ]);
 session_unset();
 session_destroy();
 header("Location: index.php");
 exit;
}
?>