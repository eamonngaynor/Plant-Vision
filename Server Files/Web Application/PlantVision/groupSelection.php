<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Website</title>
        <link rel="stylesheet" href="css/style.css">  
</head>

<style>

select#soflow, select#soflow-color {
   -webkit-appearance: button;
   -webkit-border-radius: 2px;
   -webkit-box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
   -webkit-padding-end: 20px;
   -webkit-padding-start: 2px;
   -webkit-user-select: none;
   background-image: url(http://i62.tinypic.com/15xvbd5.png), -webkit-linear-gradient(#FAFAFA, #F4F4F4 40%, #E5E5E5);
   background-position: 97% center;
   background-repeat: no-repeat;
   border: 1px solid #AAA;
   color: #555;
   font-size: inherit;
   margin: 20px;
   overflow: hidden;
   padding: 5px 10px;
   text-overflow: ellipsis;
   white-space: nowrap;
   width: 300px;
}
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
$imageId = $_SESSION['imageId'];
	
if(!isset($_SESSION['login_user'])){
   header("Location:login.php");
}

$username = $_SESSION['login_user'];
$result = mysqli_query($db,"SELECT * FROM user WHERE username = '$username'");
$row = mysqli_fetch_array($result);
$user_id = $row[0];


//Connect to our MySQL database using the PDO extension.
$pdo = new PDO('mysql:host=mysql3792.cp.blacknight.com;dbname=db1480761_plantvision', 'u1480761_Eamonn', 'Fabregas@2019'); 
//Our select statement. This will retrieve the data that we want.
$sql = "SELECT distinct plant_group FROM db_images WHERE user_id = '$user_id'";
//Prepare the select statement.
$stmt = $pdo->prepare($sql);
//Execute the statement.
$stmt->execute();
//Retrieve the rows using fetchAll.
$plants = $stmt->fetchAll();




if (isset($_POST['analyze'])){
    $group = $_POST["group"];
    //$plantN = $_POST["plantN"];
	$imageId = $_SESSION['imageId'];	

	$query = mysqli_query($db, "UPDATE db_images SET plant_group = '$group' WHERE id = '$imageId'");
	//$query = mysqli_query($db, "UPDATE db_images SET name = '$plantN' WHERE id = '$imageId'");
	echo "<script type='text/javascript'>alert('Details successfully changed')</script>";
	header('Location: http://www.c00197458.candept.com/PlantVision/manageIndex.php');		
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
      <input type="text" name = "plantN" placeholder = "Plant name: <?php echo $row['name']?>"disabled />	
      <select id = "soflow" name="group" >
        <?php foreach($plants as $plant): ?>
        <option value="<?= $plant['plant_group']; ?>"><?= $plant['plant_group']; ?></option>
        <?php endforeach; ?>
        </select> <br><br>
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
    
  
