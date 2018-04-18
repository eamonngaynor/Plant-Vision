<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Plant Vision</title>
        <link rel="stylesheet" href="css/style.css">  
</head>

<style>
div.polaroid {
  height: 75%;
  width: 100%;
  background-color: white;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  margin-bottom: 25px;
}

div.container {
  text-align: center;
  padding: 5px 5px;
}
</style>



<?php
include 'config.php';
session_start();
$plant = $_SESSION['plant'];
if(!isset($_SESSION['login_user'])){
   header("Location:login.php");
}

if (isset($_POST['analyze'])){
    $group = $_POST["group"];
    $plantN = $_POST["plantN"];
	echo $group;
	echo $plantN;
	$imageId = $_SESSION['imageId'];	

	if($group != "" && $plantN != ""){
		$query = mysqli_query($db, "UPDATE db_images SET plant_group = '$group' WHERE id = '$imageId'");
		$query = mysqli_query($db, "UPDATE db_images SET name = '$plantN' WHERE id = '$imageId'");
		echo "<script type='text/javascript'>alert('Details successfully changed')</script>";
		header('Location: http://www.c00197458.candept.com/PlantVision/chooseImage.php');
	}
	else{
		echo "<script type='text/javascript'>alert('Changes cannot be blank')</script>";
	}
	
	
}



$username = $_SESSION['login_user'];
$result = mysqli_query($db,"SELECT * FROM user WHERE username = '$username'");
$row = mysqli_fetch_array($result);
$user_id = $row[0];

$result = mysqli_query($db,"SELECT * FROM db_images WHERE name = '$plant' AND user_id = '$user_id'");
$row = mysqli_fetch_array($result);
$image = $row['url'];
$_SESSION['imageId'] = $row['id'];
?>




<!-- ----------------------HTML------------------------ --> 
<body>
 
  <div class="form">			
	
    <form  method="POST" class="login-form" >
      <input type="text" style = "border: 2px solid red;" name = "plantN" placeholder = "Plant name: <?php echo $row['name']?>"enabled />	
		<p align = "right" class="message"><a href="groupSelection.php">Choose from pre-defined group</a></p>
      <input type="text" style = "border: 2px solid red;"name = "group" placeholder= "Plant group: <?php echo $row['plant_group']?> " enabled />
      <input type="text"  placeholder= "Time taken: <?php echo $row['datetime']?> " disabled />
		
      <div class="polaroid">
      <img src= "<?php echo $image?>" alt="" style="width:100%">
      <div class="container">
      <p><?php echo $row['name']?>, <?php echo $row['plant_group']?></p>
      </div>
      </div>
      <button name = "analyze" type = "submit">Confirm</button>
      <p class="message">Changed your mind? <a href="chooseImage.php"> Go back</a></p>
    </form>

	
  </div>


</body>
</html> 
    
  
