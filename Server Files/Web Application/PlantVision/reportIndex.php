<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	 <link rel="stylesheet" href="css/style.css">  
<title>Plant Vision</title>
</head>
<body>
<br><br><br><br><br><br><br><br><br><br><br><br>

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
</style>


<?php
include 'config.php';
session_start();
if(!isset($_SESSION['login_user'])){
   header("Location:login.php");
}

if (isset($_POST['analyze'])){
	$option = $_POST['plant'];
    $_SESSION['group'] = $option;
    header("Location:report.php");
}

$username = $_SESSION['login_user'];
$result = mysqli_query($db, "SELECT * FROM user WHERE username= '$username'");
$row = mysqli_fetch_array($result);	
$id = $row[0];



//Connect to our MySQL database using the PDO extension.
$pdo = new PDO('mysql:host=mysql3792.cp.blacknight.com;dbname=db1480761_plantvision', 'u1480761_Eamonn', 'Fabregas@2019'); 
//Our select statement. This will retrieve the data that we want.
$sql = "SELECT DISTINCT plant_group FROM db_images WHERE user_id = '$id'";
//Prepare the select statement.
$stmt = $pdo->prepare($sql);
//Execute the statement.
$stmt->execute(); 
//Retrieve the rows using fetchAll.
$plants = $stmt->fetchAll();

?>

<form method="post" name = "plant">
<div class="form">	
<font size = "2"> Please select <b> Group </b> to which Leaf belongs </font>
<select id = "soflow" name="plant" >
<?php foreach($plants as $plant): ?>
<option value="<?= $plant['plant_group']; ?>"><?= $plant['plant_group']; ?></option>
<?php endforeach; ?>
</select> <br><br>
<button type = "submit" name = "analyze" >Analyze</button><br><br>
</form>	       

<!-- Logout message -->
<p class="message">Something else? <a href="home.php">Go back</a></p>
    </form>
	
</div>
</body>
</html>