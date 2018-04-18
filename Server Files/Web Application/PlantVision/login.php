<!DOCTYPE html>
<html lang="en" >
<head>
  
  <title>Plant Vision</title>
        <link rel="stylesheet" href="css/style.css">  
</head>

<script>
//----------- Scroll function for Sign up and Login--
$('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});


</script>



<!------------------------------------------ PHP ----------------------------------------------------------------------------------------------------------------------------------->  

<?php
//--------------- LOGIN -----------------------------------------------------------------------------------------------------------------------------------------------------------------

if (isset($_POST['login'])){
	
include("config.php");
session_start();


//Gathering inputs from form
$username=$_POST['username'];
$password=$_POST['password'];
$username = strip_tags($username);
htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

 
//Initial query on the table
$query = mysqli_query($db, "SELECT * FROM user WHERE username = '".$username."' and password = '".$password."'" );
$result = mysqli_query($db,"SELECT * FROM user WHERE username='$username' ");
//$sessionQuery = mysqli_query($db,"SELECT * FROM activesession WHERE sessionID ='$strForSession' ");
$row = mysqli_fetch_array($result);	
//$rowSes = mysqli_fetch_array($sessionQuery);	

/*
echo "<br>";
echo "Current attempts: ";
echo $rowSes['counter'];
*/

/*
if($rowSes['counter'] >= 3){	
	echo "<script type='text/javascript'>alert('You are locked out')</script>";	
}
else{
*/
	if ($password == $row['password']){
				//Assigning the username to a session
				$_SESSION['login_user']=$username;
				header('Location: http://www.c00197458.candept.com/PlantVision/home.php');	
			 }

		else{
				echo "<script type='text/javascript'>alert('Incorrect Login')</script>";	
				}
}
// --------------------- END/ Login -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------






















//---------------Register--------------------------------------------------------------------------------------------------------------------------------------------------------------
if (isset($_POST['register'])){

include("config.php");
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//Taking input from POST form 
$username=$_POST['uname'];
$username = strip_tags($username);
$password=$_POST['pword'];
$name=$_POST['name'];
$email=$_POST['email'];
$password2=$_POST['pword2'];

htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

$result = mysqli_query($db,"SELECT * FROM user WHERE username='$username' ");

if($result && mysqli_num_rows($result)>0){ //Checking to see if username is already taken
  echo "<script type='text/javascript'>alert('Username is already taken, user creation unsuccessful')</script>";
}

else{
	//Checking if password is equal to the re-entered password 
	if($password==$password2){
		$pattern = "/^.*(?=.{7,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/";   //Password Pattern.
		$passwordresult = preg_match($pattern,$password); //Checking password entered against pattern

		if($passwordresult == 0){	//if the password is 0 it does not meet requirements (did not match pattern)
		echo "<script type='text/javascript'>alert('Password does not meet requirements')</script>";
		}

		else{ //if password is ok, proceed and insert the user ot the table
		//$password = password_hash($password, PASSWORD_DEFAULT); 
		$sql = "INSERT INTO user (email, name, username, password) VALUES ('$email', '$name', '$username', '$password')"; //Insert query

			if ($conn->query($sql) === TRUE){ //checking connection to the table/db
				echo "<script type='text/javascript'>alert('User created successfully. Please Log in.')</script>";
			} 
			
			else{
			echo "Error: " . $sql . "<br>" . $conn->error;} //connect_error message
		}		
	}
	else{
		echo "<script type='text/javascript'>alert('Passwords do not match, user not created')</script>";	//if the passwords did not match
		}
	$conn->close();	
 } 
}
//--------------- END/Register ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
?>

<!------------------------------------------ /PHP --------------------------------------------------------------------------------------------------------------------------------------------------------------------->  













<!------------------------------------------ HTML ----------------------------------------------------------------------------------------------------------------------------------------------------------------------->  


<!-- Page BODY -->
<body>
  <div class="login-page">
  <div class="form">		
	<!-- Registration -->
    <form name = "register" id = "register" method ="post" class="register-form" >
	  <input name = "name" type="text" placeholder="Name"   required />
      <input name = "uname" type="text" placeholder="Username"   required />							
	  <input name = "email" type="email" placeholder="E-mail address"   />
      <input name = "pword" type="password" placeholder="Enter your password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
	  title = "Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required /> <!-- Pattern for password, but aslo validated in PHP -->
	  
      <input name = "pword2" type="password" placeholder="Re-enter your password"  />	
      <button name = "register" type = "submit" >create</button> 
	  <!-- Login message -->
      <p class="message">Already registered? <a href="#">Sign In</a></p>
    </form>	
		
			
	<!-- Log in -->
    <form  method="POST" class="login-form" >
	  <img src="leaf.png" alt="leaf" align = "left" height="250" width="300">
      <input type="text" name ="username" placeholder="Username" required />
      <input id = "password" type="password" name ="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
	  title = "Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required />
      <button name = "login" type = "submit">login</button>
	  <!-- Register message -->
      <p class="message">Not registered? <a href="#">Create an account</a></p>
    </form>

	
  </div>
</div>
	
	<!--Facilitates the scroll for Registration form -->	  
	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
	<!--Facilitates the JavaScript file -->
	<script type="text/javascript" src="js/index.js"></script>

</body>
</html>

<!------------------------------------------ /HTML --------------------------------------------------------------------------------------------------------------------------------->  