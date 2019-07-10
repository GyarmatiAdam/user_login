<?php
ob_start();
session_start(); // start a new session or continues the previous
if( isset($_SESSION['customers'])!="" ){
 header("Location: home.php" ); // redirects to home.php
}
include_once 'dbconnect.php';
$error = false;
if ( isset($_POST['btn-signup']) ) {
 
 // sanitize user input to prevent sql injection
 $first_name = trim($_POST['first_name']);
 $last_name = trim($_POST['last_name']);
 $dob = $_POST['dob'];
 $pass = $_POST['pass'];

 $email = trim($_POST['c_email']);
 $email = strip_tags($_POST['c_email']);
 $email = htmlspecialchars($_POST['c_email']);

 $username= trim($_POST['username']);
 $username=strip_tags($_POST['username']);
 $username= htmlspecialchars($_POST['username']);

  //trim - strips whitespace (or other characters) from the beginning and end of a string
  // strip_tags — strips HTML and PHP tags from a string
 // htmlspecialchars converts special characters to HTML entities
 /*
 $email = strip_tags($email);
 $email = htmlspecialchars($email);
            function escape($par){
                $ress = trim($_POST['par']);
                $ress = strip_tags($_POST['par']);
                $ress = htmlspecialchars($_POST['par']);

                return $ress;
            }
$email = escape('c_email');
$username = escape('username');
          */
          if (!preg_match("/^[a-zA-Z ]+$/",$first_name)){
            $error = true ;
            $firstnameError = "Please enter your first name.";
            }

          if (!preg_match("/^[a-zA-Z ]+$/",$last_name)){
            $error = true ;
            $lastnameError = "Please enter your last name.";
            }

          if (empty($dob)){
            $error = true ;
            $dobError = "Please enter your date of borth.";
            }

  // basic username validation
 if (empty($username)) {
  $error = true ;
  $usernameError = "Please enter your full username.";
 } else if (strlen($username) < 5) {
  $error = true;
  $usernameError = "username must have at least 5 characters.";
 } else if (!preg_match("/^[a-zA-Z ]+$/",$username)) {
  $error = true ;
  $usernameError = "username must contain alphabets and space.";
 }

 //basic email validation
  if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
  $error = true;
  $emailError = "Please enter valid email address." ;
 } else {
  // checks whether the email exists or not
  $query = "SELECT c_email FROM customers WHERE c_email='$email'";
  $result = mysqli_query($conn, $query);
  $count = mysqli_num_rows($result);
  if($count!=0){
   $error = true;
   $emailError = "Provided Email is already in use.";
  }
 }
 // password validation
  if (empty($pass)){
  $error = true;
  $passError = "Please enter password.";
 } else if(strlen($pass) < 6) {
  $error = true;
  $passError = "Password must have atleast 6 characters." ;
 }
 else if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $pass)) {
    echo 'The password does not meet the requirements!<br> 
    Has to contain a number, a letter and a special character (!@#$%)<br>
    Has to be 8-12 characters';
 }

 // password hashing for security
$password = hash('sha256' , $pass);


 // if there's no error, continue to signup
 if( !$error ) {
  
  $query = "INSERT INTO customers (first_name, last_name , c_email, dob, pass, username) VALUES ('$first_name', '$last_name', '$email', '$dob', '$password', '$username')";
  $res = mysqli_query($conn, $query);

  if ($res) {
   $sucErrMSG = "Successfully registered, you may login now";
    
    unset($first_name);
    unset($last_name);
    unset($email);
    unset($dob);
    unset($pass);
    unset($username);

  } else  {
   $errMSG = "Something went wrong, try again later..." ;
  }
  
 }

}
?>
<!DOCTYPE html> 
<html>
<head>
<title>Registration</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet"  href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"  crossorigin="anonymous">
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-sm-2">
    </div>
    <div class="col-sm-8">
   <form method="post"  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  autocomplete="off" >
    	<h2>Sign Up.</h2>
        <?php    
           if ( isset($errMSG)) { isset($sucErrMSG)
        ?>  
        <div  class="alert alert-danger" ><?php echo  $errMSG; ?></div>
        <?php 
            }
            if ( isset($sucErrMSG)) {
        ?>
        <div  class="alert alert-success" ><?php echo  $sucErrMSG; ?></div>
        <?php 
            }
        ?>
        
        <input type ="text" name="first_name"  class ="form-control"  placeholder ="Enter First Name"  maxlength ="50"   value = "<?php echo $first_name ?>"  />
            <span class = "text-danger" > <?php   echo  $firstnameError; ?> </span>
        <input type ="text"  name="last_name"  class ="form-control"  placeholder ="Enter Last Name"  maxlength ="50"   value = "<?php echo $last_name ?>"  />
            <span class = "text-danger" > <?php   echo  $lastnameError; ?> </span>
        <input type = "email"   name = "c_email"   class = "form-control"   placeholder = "Enter Your Email"   maxlength = "40"   value = "<?php echo $email ?>"  />
            <span class = "text-danger" > <?php   echo  $emailError; ?> </span>
        <input type = "date"   name = "dob"   class = "form-control"   placeholder = "YYYY-MM-DD"   maxlength = "40"   value = "<?php echo $dob ?>"  />
            <span class = "text-danger" > <?php   echo  $dobError; ?> </span>
        <input type = "password"   name = "pass"   class = "form-control"   placeholder = "Enter Password"   maxlength = "12"  value = "<?php echo $pass ?>" />
            <span class = "text-danger" > <?php   echo  $passError; ?> </span>
        <input type ="text"  name="username"  class ="form-control"  placeholder ="Enter username"  maxlength ="50"   value = "<?php echo $username ?>"  />
            <span class = "text-danger" > <?php   echo  $usernameError; ?> </span>
        <hr/>
        <button type = "submit"  class = "btn btn-block btn-primary"  name= "btn-signup" >Sign Up</button>
            
   </form><hr/>
        <a href="index.php" class="btn btn-primary">Sign in Here...</a>
    </div>
    <div class="col-sm-2">
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body >
</html >
<?php  ob_end_flush(); ?>