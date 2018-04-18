<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	 <link rel="stylesheet" href="css/style.css">  
<title>Plant Vision</title>
</head>



<body>


<!------------------------------------------ PHP ----------------------------------------------------------------------------------------------------------------------------------->  
 <?php 
 //Checking Session----
session_start();
//Checking to see if user is authenticated to access pages
if(!isset($_SESSION['login_user'])){
   header("Location:login.php");
}


if (isset($_POST['changeP'])){
	include("config.php");
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error){
		die("Connection failed: " . $conn->connect_error);
	} 
	//Grabbing variables from form via POST 
	$user=$_SESSION['login_user'];
	$oPassword=$_POST['oPWord'];
	$nPassword=$_POST['nPWord'];
	$nPassword1=$_POST['nPWord1'];
	$result = mysqli_query($conn,"SELECT * FROM user WHERE username='$user' ");
	$row = mysqli_fetch_array($result);
	
	
	//checking old password in the database to ensure it is correct
	if($oPassword == $row['password']){
		//checking new password against re-entered password
		if($nPassword==$nPassword1){
			$pattern = "/^.*(?=.{7,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/";   //Password Pattern.
			$passwordresult = preg_match($pattern,$nPassword); //Checking password against pattern
			
			if($passwordresult == 0){	//if the password is 0 it does not meet requirements/did not match pattern
				echo "<script type='text/javascript'>alert('Password does not meet requirements')</script>";
			}
			
			else{ //if ok, go ahead and insert to table the new user
			    //$nPassword = password_hash($nPassword, PASSWORD_DEFAULT); 
				$sql = "UPDATE user SET password ='$nPassword' WHERE username='$user'"; //Update sql statement 
				if ($conn->query($sql) === TRUE){ //checking connection to the table/db
					echo "<script type='text/javascript'>alert('Password successfully changed. Please log in using your new credentials.')</script>";
					session_destroy();
					echo "<script language='javascript' type='text/javascript'> location.href='http://www.c00197458.candept.com/PlantVision/login.php' </script>";
				} 
			
				else{
					echo "Error: " . $sql . "<br>" . $conn->error;}
			}	
		}
		else{
			echo "<script type='text/javascript'>alert('Passwords do not match, Password not changed')</script>";	
		}
	}	
	else{
		echo "<script type='text/javascript'>alert('Old password is incorrect.')</script>";
	}
}
	





?>

<!------------------------------------------ /PHP ----------------------------------------------------------------------------------------------------------------------------------->  








<!------------------------------------------ HTML --------------------------------------------------------------------------------------------------------------------------------->  
 <br><br><br>
 <div class="form">		
			
	<!-- Uploading Option -->
	<form method="post">
      <input name = "oPWord" type="password" placeholder="Old Password"   required />							
	  <input name = "nPWord" type="password" placeholder="New Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
	  title = "Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required /> <!-- Pattern for password, but aslo validated in PHP -->
	  
      <input name = "nPWord1" type="password" placeholder="Re-enter New Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
	  title = "Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required /> <!-- Pattern for password, but aslo validated in PHP -->
	  
	  <button name = "changeP" type = "submit" >Change Password</button> 
	  <p class="message">Remembered it? <a href="home.php">Go Back</a></p>
	</form>
    	
</div>
</body>
</html>
<!------------------------------------------ /HTML --------------------------------------------------------------------------------------------------------------------------------->  