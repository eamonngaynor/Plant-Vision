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

include 'config.php';  
//Creating active session
session_start();

if(!isset($_SESSION['login_user'])){
   header("Location:login.php");
}


if (isset($_POST['analyze'])){
	header("Location:manageIndex.php");	
}

if (isset($_POST['report'])){
	header("Location:reportIndex.php");	
}

if (isset($_POST['change'])){
	header("Location:change.php");	
}

	
?>
<!------------------------------------------ /PHP ----------------------------------------------------------------------------------------------------------------------------------->  








<!------------------------------------------ HTML --------------------------------------------------------------------------------------------------------------------------------->  
 <br><br><br>
 <div class="form">	
	 <p align = "left" class="message">Welcome<b><font style="color:black;" size="4">
	<?php
	//login session is the user that is currently active in the session
	$login_session=$_SESSION['login_user'];
	echo $login_session;
	?>
	<img src="leaf.png" alt="leaf" align = "left" height="250" width="300">
	 
	<!-- Option Menu -->
	
	
	
	</font></b>
	</p><br>
	<!-- Analyze Option -->
	<form method="post">
    <button type = "submit" name = "analyze" >Manage Leaves</button><br><br>
	</form>
    <!-- Reporting Option -->
	<form method="post">
	<button type = "submit" name = "report" >Analyze Plant</button><br><br>
	</form>
	
	<!-- Logout message -->
    <p class="message">Finished? <a href="logout.php">Logout</a></p>
	 
	 <!-- Logout message -->
    <p class="message">Need to change your password? <a href="change.php">Change Password</a></p>
    </form>

	
</div>
</body>
</html>
<!------------------------------------------ /HTML --------------------------------------------------------------------------------------------------------------------------------->  