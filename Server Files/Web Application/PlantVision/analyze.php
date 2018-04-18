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
	

#home{
    position:absolute;
    top: 3%;
    left: 2%;
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
	header("Location:editor.php");	
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
	
<div id="home">
<a href = "home.php">
<img src="homeb.png" title="Go Home" width="50" height="50" alt="" onclick = "home.php">
</a>
</div>	

 
  <div class="form">			
	
    <form  method="POST" class="login-form" >
	   <p align = "right" class="message"><a href="edit.php">Edit Plant Name</a></p>
      <input type="text" placeholder = "Plant name: <?php echo $row['name']?>"disabled />
		 <p align = "right" class="message"><a href="edit.php">Edit Plant Group</a></p>
      <input type="text"  placeholder= "Plant group: <?php echo $row['plant_group']?> " disabled />
      <input type="text"  placeholder= "Time taken: <?php echo $row['datetime']?> " disabled />
		
      <div class="polaroid">
      <img src= "<?php echo $image?>" alt="" style="width:100%">
      <div class="container">
      <p><?php echo $row['name']?>, <?php echo $row['plant_group']?></p>
      </div>
      </div>
      <button name = "analyze" type = "submit">Analyze</button>
    <p class="message">Need more details about the plant? <a href="weatherD.php">Check weather and location</a></p>
    <p class="message">Not the correct plant? <a href="chooseImage.php">Choose another</a></p>
    </form>

	
  </div>


</body>
</html> 
    
  
