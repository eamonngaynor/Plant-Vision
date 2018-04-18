<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link rel="stylesheet" href="css/style.css">  
<link rel="stylesheet" href="css/circle.css">
<title>Plant Vision</title>
</head>

<style>	

BUTTON{
    background:  yellow;
    color: white;
}
.div {
    width: 920px;
    padding: 2px;
    height: 490px;
    border: 2px solid gray;
    background: white;
    position:absolute;
    top: 20%;
    left: 25%;  
}

.box1{
    background-color: white;
    border: 5px solid gray;
    width: 350px;
    height: 350px;
    position:absolute;
    top: 15%;
    left: 110%; 
   }

.form3{
    position:fixed;
    top: 10%;
    left: 5%; 
   
}

.table {
    position:absolute;
    top: 3%;
    left: 4%;  
    background-color: white;
}
		
	
</style>


<?php 
session_start();
$plant = $_SESSION['plant'];
$_SESSION['plant'] = $plant;
include 'config.php';
if(!isset($_SESSION['login_user'])){
   header("Location:login.php");
}	

$username = $_SESSION['login_user'];
$result = mysqli_query($db,"SELECT * FROM user WHERE username = '$username'");
$row = mysqli_fetch_array($result);
$user_id = $row[0];

$result = mysqli_query($db,"SELECT * FROM db_images WHERE name = '$plant' AND user_id = '$user_id'");
$row = mysqli_fetch_array($result);
$imageIdentifier = $row[0];
$url = $row[1];

$result = mysqli_query($db,"SELECT * FROM imageResult WHERE imageID = '$imageIdentifier'");
$colours = [];
$counter = 0;
$i = 0;
while ($row = mysqli_fetch_array($result)) {
    $id[$i] = $row[0];
    $colour[$i] =  $row[2];
    $colour[$i] = dechex($colour[$i]);
    $percentage[$i] = $row[3];
    $counter++;
	$diseased[$i] = $row[5];
    $i++;	
}

$result = mysqli_query($db,"SELECT * FROM imageResult WHERE imageID = '$imageIdentifier' AND diseaseM = 'Diseased'");
$row = mysqli_fetch_array($result);
$displayDisease = $row[3];

?>


<body>
<div class="c100 p10 orange big">
	<span><?php echo $displayDisease."%";?></span>
  <div class="slice">
    <div class="bar"></div>
    <div class="fill"></div>
  </div>
</div>	

	
	
<div class = "div">	
<div class = "table">
<table border="4">
<tr><th colspan="7"><?php echo $plant ?></th></tr>
	<tr><td><b>No.</b></td><td><b>Color</b></td><td><b>Coverage</b></td><td><b>Marked</b></td><td><b>Color value</b></td><td><b>Remove</b></td><td><b>Disease</b></td></tr>
<?php
$number = 1;
for ($i = 0; $i < $counter; $i++)
{
    echo "<tr><td>$number</td><td bgcolor=".$colour[$i]." width=16 height=16></td>&nbsp;<td>".$percentage[$i].
    "%</td><td><b>$diseased[$i]</b></td><td>#$colour[$i]</td><td><button><a href='delete.php?id=".$id[$i]."'>Delete</a></button></td><td><button><a href='mark.php?	  id=".$id[$i]."'>Mark As</a></button></td></tr>";
    $number++;
}
?>
</div>
<div class = "box1">
<img  style='height: 100%; width: 100%; object-fit: contain' src="<?php echo $url ?>">
</div>
<p class="message">Not the correct plant? <a href="chooseImage.php">Choose another</a></p>


</div> 


</body>
</html>



      