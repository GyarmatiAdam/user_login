<?php
ob_start();
session_start();
require_once 'dbconnect.php';

// it will never let you open index(login) page if session is set
if ( isset($_SESSION['customers'])!="" ) {
 header("Location: home.php");
 exit;
}

$error = false;

if( isset($_POST['btn-login']) ) {

  // prevent sql injections/ clear user invalid inputs
  $email = trim($_POST['c_email']);
  $email = strip_tags($_POST['c_email']);
  $email = htmlspecialchars($_POST['c_email']);

  $pass = $_POST['pass'];

 //$pass = trim($_POST['pass']);
 //$pass = strip_tags($pass);
 //$pass = htmlspecialchars($pass);
 // prevent sql injections / clear user invalid inputs 

 if(empty($email)){
  $error = true;
  $emailError = "Please enter your email address.";
 } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
  $error = true;
  $emailError = "Please enter valid email address.";
 }

 if (empty($pass)){
  $error = true;
  $passError = "Please enter your password." ;
 }

 // if there's no error, continue to login
 if (!$error) {
  
  $password = hash('sha256', $pass); // password hashing

  $res=mysqli_query($conn, "SELECT cust_id, first_name, pass FROM customers WHERE c_email= '$email'" );
  $row=mysqli_fetch_array($res, MYSQLI_ASSOC);//mysqli_fetch_assoc($res)
  $count = mysqli_num_rows($res); // if uname/pass is correct it returns must be 1 row 
  
  if( $count == 1 && $row['pass' ]==$password ) {
   $_SESSION['customers'] = $row['cust_id'];
   header( "Location: home.php");
  } else {
   $errMSG = "Incorrect Credentials, Try again..." ;
  }
  
 }

}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href ="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"  crossorigin="anonymous">
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-sm-2">
    </div>
    <div class="col-sm-8">
   <form method="post"  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete= "off">
    <h2>Sign In.</h2 >
        <hr/>
    <?php
    if ( isset($errMSG) ) {
        echo  $errMSG; 
    ?>          
    <?php
        }
    ?>
    <input  type="email" name="c_email"  class="form-control" placeholder= "Your Email" value="<?php echo $email; ?>"  maxlength="40" />
        <span class="text-danger"><?php  echo $emailError; ?></span >
    <input  type="password" name="pass"  class="form-control" placeholder ="Your Password" maxlength="12"  />
        <span  class="text-danger"><?php  echo $passError; ?></span>
            <hr/>
    <button  type="submit" name= "btn-login">Sign In</button>
            <hr/>
    </form>    
    
    <a href="register.php">Sign Up Here...</a>
    </div>
    <div class="col-sm-2">
    </div>
    </div>
    </div>
   </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
<?php ob_end_flush(); ?>