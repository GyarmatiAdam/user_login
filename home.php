<?php
ob_start();
session_start();
require_once 'dbconnect.php';

// if session is not set this will redirect to login page
if( !isset($_SESSION['customers' ]) ) {
 header("Location: index.php");
 exit;
}
// select logged-in users details 
$res=mysqli_query($conn, "SELECT * FROM customers WHERE cust_id=".$_SESSION['customers']);
$userRow=mysqli_fetch_array($res, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<title>Welcome - <?php echo $userRow['first_name']; ?></title>
</head>
<body >
<div class="container">
  <div class="row">
    <div class="col-sm-2">
    </div>
    <div class="col-sm-6">
        <h2>Welcome <?php echo $userRow['first_name']; ?></h2>
        <a class="btn btn-warning" href="logout.php?logout">Sign Out</a>
    </div>
    <div class="col-sm-4">
        
        <h2><?php echo $userRow['first_name'];echo '<br>'; echo $userRow['last_name']; ?></h2>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
<?php ob_end_flush(); ?>